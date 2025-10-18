<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 
use Illuminate\Validation\ValidationException; // <-- Added for custom validation errors

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
        $gender_values = array_column(GenderEnum::cases(), 'value');
        $marital_status_values = array_column(MaritalStatusEnum::cases(), 'value');

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'street_address' => 'nullable|string',
            'city_address' => 'nullable|string',
            
            // Fix for nullable uniqueness on creation
            'contact_number' => ['nullable', 'regex:/(09)[0-9]{9}/', Rule::unique('profiles')->where(fn ($query) => $query->whereNotNull('contact_number'))],
            'email_address' => ['nullable', 'email', Rule::unique('profiles')->where(fn ($query) => $query->whereNotNull('email_address'))],
            
            'birthday' => 'nullable|date',
            'gender' => 'required|in:' . implode(',', $gender_values),
            'marital_status' => 'required|in:' . implode(',', $marital_status_values),
            'joined_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
        ]);

        // Inject the default 'Active' status
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
        // Eager-load the roles currently assigned to this profile
        $profile->load('roles'); 
        
        // Load ALL available roles
        $roles = Role::orderBy('name')->get(); 

        // Pass both the profile and the list of ALL roles to the view
        return view('profiles.profile-list.data', [
            'profile' => $profile,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, Profile $profile)
    {
        $gender_values = array_column(GenderEnum::cases(), 'value');
        $marital_status_values = array_column(MaritalStatusEnum::cases(), 'value');
        $member_status_values = array_column(MemberStatusEnum::cases(), 'value');
        $role_ids = Role::pluck('id')->toArray();

        // 1. Perform standard validation
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'street_address' => 'nullable|string',
            'city_address' => 'nullable|string',
            
            // Fix for nullable uniqueness on update
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
            'member_status' => ['required', Rule::in($member_status_values)], 
            
            // Base role field is required only if status is Active
            'base_role' => [
                Rule::requiredIf($request->input('member_status') === MemberStatusEnum::ACTIVE->value),
                Rule::in(['volunteer', 'staff'])
            ], 
            
            'roles' => ['nullable', 'array'], 
            'roles.*' => ['integer', Rule::in($role_ids)], 
        ]);

        $validatedData['last_updated_by_id'] = Auth::id();

        // 2. Custom Business Logic Validation (only if Active)
        if ($validatedData['member_status'] === MemberStatusEnum::ACTIVE->value) {
            
            $submittedBaseRole = $validatedData['base_role'];
            $submittedRoleIds = $validatedData['roles'] ?? [];
            $submittedRoleSlugs = Role::whereIn('id', $submittedRoleIds)->pluck('slug')->toArray();
            
            // Define allowed slugs based on the last refactored Blade template
            $volunteerAllowedSlugs = [
                'ministry_leader', 'ministry_assistant', 'pastor_in_training', 'team_member',
            ];
            $staffAllowedSlugs = [
                'admin', 'ministry_leader', 'ministry_assistant', 'pastor_in_training', 
                'associate_pastor', 'board', 'lead_pastor', 'church_advisor',
            ];

            // A. Base Role Constraint Check
            if ($submittedBaseRole === 'volunteer') {
                $allowedSlugs = $volunteerAllowedSlugs;
            } elseif ($submittedBaseRole === 'staff') {
                $allowedSlugs = $staffAllowedSlugs;
            } else {
                $allowedSlugs = []; 
            }

            // Check if every submitted specific role slug is within the allowed set
            $invalidSlugs = array_diff($submittedRoleSlugs, $allowedSlugs);
            
            if (!empty($invalidSlugs)) {
                $invalidNames = Role::whereIn('slug', $invalidSlugs)->pluck('name')->implode(', ');
                throw ValidationException::withMessages([
                    'roles' => 'The selected base role (' . ucfirst($submittedBaseRole) . ') does not permit the following specific roles: ' . $invalidNames . '.',
                ]);
            }

            // B. Lead Pastor / Church Advisor Count and Mutual Exclusion Check
            $isLeadPastor = in_array('lead_pastor', $submittedRoleSlugs);
            $isChurchAdvisor = in_array('church_advisor', $submittedRoleSlugs);

            // B.1. Mutual Exclusion Check
            if ($isLeadPastor && $isChurchAdvisor) {
                throw ValidationException::withMessages([
                    'roles' => 'A profile cannot be both a Lead Pastor and a Church Advisor.',
                ]);
            }
            
            // Get IDs for Lead Pastor and Church Advisor roles
            $leadPastorRoleId = Role::where('slug', 'lead_pastor')->value('id');
            $churchAdvisorRoleId = Role::where('slug', 'church_advisor')->value('id');

            // B.2. Count Limit Check (Max 2 Profiles)
            
            // Check Lead Pastor limit
            if ($isLeadPastor) {
                $leadPastorCount = DB::table('profile_role')
                    ->where('role_id', $leadPastorRoleId)
                    ->where('profile_id', '!=', $profile->id) // Exclude current profile's existing count
                    ->count();

                if ($leadPastorCount >= 2) {
                    throw ValidationException::withMessages([
                        'roles' => 'The limit of two profiles for the Lead Pastor role has been reached.',
                    ]);
                }
            }

            // Check Church Advisor limit
            if ($isChurchAdvisor) {
                $churchAdvisorCount = DB::table('profile_role')
                    ->where('role_id', $churchAdvisorRoleId)
                    ->where('profile_id', '!=', $profile->id) // Exclude current profile's existing count
                    ->count();

                if ($churchAdvisorCount >= 2) {
                    throw ValidationException::withMessages([
                        'roles' => 'The limit of two profiles for the Church Advisor role has been reached.',
                    ]);
                }
            }
        }
        
        // 3. Proceed with update and role sync
        DB::transaction(function () use ($profile, $validatedData, $request) {
            $profile->update($validatedData);

            if ($profile->member_status === MemberStatusEnum::ACTIVE) {
                $newRoleIds = $validatedData['roles'] ?? [];
                
                // Add the submitted base role ID
                if (isset($validatedData['base_role'])) {
                    $baseRole = Role::where('slug', $validatedData['base_role'])->first();
                    if ($baseRole) {
                        $newRoleIds[] = $baseRole->id;
                    }
                }
                
                $this->syncProfileRoles($profile, $newRoleIds);
            }
        });

        return redirect()->route('profiles.profile', $profile->id)->with('success', 'Profile updated successfully.');
    }

    private function syncProfileRoles(Profile $profile, array $newRoleIds)
    {
        $finalRoleIds = collect($newRoleIds);

        // 1. Ensure 'Member' role is always included for an Active profile
        $memberRole = \App\Models\Role::where('slug', 'member')->first();
        if ($memberRole) {
            $finalRoleIds->push($memberRole->id);
        }
        
        // Get the slugs of ALL roles being considered for sync (including member)
        $finalSlugs = \App\Models\Role::whereIn('id', $finalRoleIds->unique())->pluck('slug');
        
        $rolesToRemoveSlugs = [];

        // Conflict Rules check
        
        // Rule 1: Lead Pastor Conflict
        if ($finalSlugs->contains('lead_pastor')) {
            // Cannot be ministry_assistant, associate_pastor, staff, or volunteer
            $rolesToRemoveSlugs = array_merge($rolesToRemoveSlugs, [
                'ministry_assistant', 
                'associate_pastor', 
                'staff', 
                'volunteer'
            ]);
        }

        // Rule 2: Church Advisor Conflict (MOST RESTRICTIVE)
        if ($finalSlugs->contains('church_advisor')) {
            // Cannot have any other roles. We remove all slugs EXCEPT 'church_advisor' and 'member'.
            $allOtherSlugs = $finalSlugs->reject(fn($slug) => 
                $slug === 'church_advisor' || $slug === 'member'
            )->toArray();
            
            $rolesToRemoveSlugs = array_merge($rolesToRemoveSlugs, $allOtherSlugs);
        }

        // Existing Rule: Staff/Board Conflict (preventing being a 'volunteer')
        // This is necessary if 'staff' or 'board' is manually selected but 'lead_pastor' is not present.
        if (($finalSlugs->contains('staff') || $finalSlugs->contains('board')) && !$finalSlugs->contains('lead_pastor')) {
            // Only add 'volunteer' if it hasn't been added by the church_advisor rule (array_merge handles this)
            $rolesToRemoveSlugs = array_merge($rolesToRemoveSlugs, ['volunteer']);
        }


        // Get the IDs of the roles to be removed
        $uniqueRemoveSlugs = array_unique($rolesToRemoveSlugs);
        $removeIds = \App\Models\Role::whereIn('slug', $uniqueRemoveSlugs)->pluck('id')->toArray();
        
        // Final list of roles to sync: unique roles minus conflicting roles
        $finalRoleIds = $finalRoleIds->unique()->diff($removeIds)->filter()->toArray();

        // Perform the sync
        $profile->roles()->sync($finalRoleIds);
    }
}