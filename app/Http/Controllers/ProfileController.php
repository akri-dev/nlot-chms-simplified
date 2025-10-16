<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Needed for transactions

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\MemberStatusEnum;

use App\Models\Profile;
use App\Models\Role;

class ProfileController extends Controller
{
    private $profile_m;

    public function __construct(Profile $profile)
    {
        $this->profile_m = $profile;
    }

    public function index()
    {
        $all_profiles = $this->profile_m->orderBy('last_name', 'asc')->get();
        return view('profiles.index')
            ->with('all_profiles', $all_profiles);
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function save(Request $request)
    {
        // $pro_m = $this->profile_m;

        $gender_values = array_column(GenderEnum::cases(), 'value');
        $marital_status_values = array_column(MaritalStatusEnum::cases(), 'value');

        $member_status_values = array_column(MemberStatusEnum::cases(), 'value');

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'street_address' => 'nullable|string',
            'city_address' => 'nullable|string',
            'contact_number' => 'nullable|regex:/(09)[0-9]{9}/',
            'email_address' => 'nullable|email|unique:profiles,email_address',
            'birthday' => 'nullable|date',
            'gender' => 'required|in:' . implode(',', $gender_values),
            'marital_status' => 'required|in:' . implode(',', $marital_status_values),
            'joined_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
        ]);

        $validatedData['member_status'] = MemberStatusEnum::ACTIVE;

        $validatedData['added_by_id'] = Auth::id();

        $profile = Profile::create($validatedData);

        return redirect()->route('profiles')->with('success', 'New Profile created successfully.');
    }

    public function anniversaries()
    {
        return view('profiles.anniversaries');
    }

    public function profile(Profile $profile)
    {
        // 1. Eager-load the roles currently assigned to this profile
        $profile->load('roles'); 
        
        // 2. Load ALL available roles from the database
        $roles = Role::orderBy('name')->get(); // <-- FIX: Define the $roles variable

        // 3. Pass both the profile and the list of ALL roles to the view
        return view('profiles.profile-list.data', [
            'profile' => $profile,
            'roles' => $roles, // <-- FIX: Pass the $roles variable
        ]);
    }

    public function update(Request $request, Profile $profile)
    {
        $gender_values = array_column(GenderEnum::cases(), 'value');
        $marital_status_values = array_column(MaritalStatusEnum::cases(), 'value');
        $member_status_values = array_column(MemberStatusEnum::cases(), 'value');
        $role_ids = Role::pluck('id')->toArray(); // Get all valid role IDs

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'street_address' => 'nullable|string',
            'city_address' => 'nullable|string',
            'contact_number' => [
                'nullable', 
                'regex:/(09)[0-9]{9}/', 
                Rule::unique('profiles')
                    ->ignore($profile->id, 'id')
                    ->where(fn ($query) => $query->whereNotNull('contact_number'))
            ],
            'email_address' => [
                'nullable', 
                'email', 
                Rule::unique('profiles')
                    ->ignore($profile->id, 'id')
                    ->where(fn ($query) => $query->whereNotNull('email_address'))
            ],
            'birthday' => 'nullable|date',
            'gender' => ['required', Rule::in($gender_values)],
            'marital_status' => ['required', Rule::in($marital_status_values)],
            'joined_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'member_status' => ['required', Rule::in($member_status_values)], // New status
            'roles' => ['nullable', 'array'], // Array of role IDs
            'roles.*' => ['integer', Rule::in($role_ids)], // Ensure all role IDs are valid
        ]);

        $validatedData['last_updated_by_id'] = Auth::id();

        DB::transaction(function () use ($profile, $validatedData, $request) {
            // 1. Update Profile's simple attributes (including member_status)
            // Note: The setMemberStatusAttribute mutator will handle the roles sync if status is 'Inactive'.
            $profile->update($validatedData);

            // 2. Handle Role Assignments (Only if roles are in the request and status is Active)
            if ($profile->member_status === MemberStatusEnum::ACTIVE && isset($validatedData['roles'])) {
                $newRoleIds = $validatedData['roles'];
                $this->syncProfileRoles($profile, $newRoleIds);
            }
        });

        return redirect()->route('profiles.profile', $profile->id)->with('success', 'Profile updated successfully.');
    }

    private function syncProfileRoles(Profile $profile, array $newRoleIds)
    {
        $roles = Role::whereIn('id', $newRoleIds)->pluck('slug', 'id');
        $slashedRoleIds = array_keys($roles->toArray());

        $rolesToRemove = [];

        // Rules check
        if ($roles->contains('lead_pastor')) {
            // Lead Pastor cannot be 'Staff' or 'Volunteer'
            $rolesToRemove = array_merge($rolesToRemove, ['staff', 'volunteer']);
        }

        if ($roles->contains('staff') || $roles->contains('board')) {
            // Staff and Board are not Volunteers
            $rolesToRemove = array_merge($rolesToRemove, ['volunteer']);
        }

        // Ensure 'Member' is always included for an Active profile
        $memberRole = Role::where('slug', 'member')->first();
        if ($memberRole) {
            $slashedRoleIds[] = $memberRole->id;
        }

        // Get the IDs of the roles to be removed
        $roleSlugsToRemove = array_unique($rolesToRemove);
        $removeIds = Role::whereIn('slug', $roleSlugsToRemove)->pluck('id')->toArray();

        // Final list of roles to sync: remove conflicting roles from the new list.
        $finalRoleIds = collect(array_unique($slashedRoleIds))
            ->diff($removeIds)
            ->filter() // Remove nulls/zeros
            ->toArray();

        // Perform the sync
        $profile->roles()->sync($finalRoleIds);
    }
}
