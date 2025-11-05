<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\MemberStatusEnum;

use App\Models\Profile;
use App\Models\Role;
use App\Models\Marriage;

class ProfileController extends Controller
{
    private $profile_m;

    public function __construct(Profile $profile)
    {
        $this->profile_m = $profile;
    }

    public function index()
    {
        $all_profiles = $this->profile_m->with('roles')->orderBy('last_name', 'asc')->get();

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

        $member_status_values = array_column(MemberStatusEnum::cases(), 'value');

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'street_address' => 'nullable|string',
            'city_address' => 'nullable|string',

            // Fix for nullable uniqueness on creation
            'contact_number' => ['nullable', 'regex:/(09)[0-9]{9}/', Rule::unique('profiles')->where(fn($query) => $query->whereNotNull('contact_number'))],
            'email_address' => ['nullable', 'email', Rule::unique('profiles')->where(fn($query) => $query->whereNotNull('email_address'))],

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
        // --- 1. Find all married males that are NOT listed as a husband yet ---
        $male_unlinked_profiles = Profile::where('marital_status', 'married')
            ->where('gender', 'male')
            ->whereDoesntHave('marriageAsHusband') // Check if they are linked via the 'husband_id' column
            ->get();

        // --- 2. Find all married females that are NOT listed as a wife yet ---
        $female_unlinked_profiles = Profile::where('marital_status', 'married')
            ->where('gender', 'female')
            ->whereDoesntHave('marriageAsWife') // Check if they are linked via the 'wife_id' column
            ->get();

        // The combined list of profiles who need to be linked for their anniversary
        $profiles_needing_link = $male_unlinked_profiles->merge($female_unlinked_profiles);

        // --- OPTIONAL: Get a list of profiles whose marriages ARE recorded ---

        // We can use whereHas on either relationship, but we'll use 'marriageAsHusband'
        // since this represents the primary record entry direction.
        $linked_married_profiles = Profile::where('marital_status', 'married')
            ->where(function ($query) {
                $query->whereHas('marriageAsHusband')
                    ->orWhereHas('marriageAsWife');
            })
            ->get();

        // Separate the linked profiles by gender (if needed for display)
        $male_linked_profiles = $linked_married_profiles->where('gender', 'male');
        $female_linked_profiles = $linked_married_profiles->where('gender', 'female');

        $marriages = Marriage::with(['husband', 'wife'])->get();

        return view('profiles.anniversaries', [
            // Profiles NOT in the marriages table (need linking/attention)
            'male_unlinked_profiles' => $male_unlinked_profiles,
            'female_unlinked_profiles' => $female_unlinked_profiles,

            // Profiles THAT ARE in the marriages table (for displaying anniversaries)
            'male_linked_profiles' => $male_linked_profiles,
            'female_linked_profiles' => $female_linked_profiles,

            // The combined list of linked profiles
            'anniversary_profiles' => $linked_married_profiles,

            'marriages' => $marriages,

            'profiles_needing_link' => $profiles_needing_link
        ]);
    }

    public function profile(Profile $profile)
    {
        // Eager-load the roles currently assigned to this profile
        $profile->load('roles');

        // Load ALL available roles
        $roles = Role::orderBy('name')->get();

        // 1. Define the specific role display order slugs for the Blade view
        $volunteerDisplayOrderSlugs = [
            'ministry_leader',
            'ministry_assistant',
            'team_member',
            'pastor_in_training',
        ];

        $staffDisplayOrderSlugs = [
            'lead_pastor',
            'church_advisor',
            'board',
            'associate_pastor',
            'admin',
            'ministry_leader',
            'ministry_assistant',
            'pastor_in_training',
        ];

        // 2. Fetch current counts for limited roles (Lead Pastor and Church Advisor)
        // We get the count of OTHER profiles that have the role.
        $leadPastorRoleId = Role::where('slug', 'lead_pastor')->value('id');
        $churchAdvisorRoleId = Role::where('slug', 'church_advisor')->value('id');

        $limitedRoleCounts = [
            'lead_pastor' => DB::table('profile_role')
                ->where('role_id', $leadPastorRoleId)
                ->where('profile_id', '!=', $profile->id)
                ->count(),
            'church_advisor' => DB::table('profile_role')
                ->where('role_id', $churchAdvisorRoleId)
                ->where('profile_id', '!=', $profile->id)
                ->count(),
        ];


        // Pass all data to the view
        return view('profiles.profile-list.data', [
            'profile' => $profile,
            'roles' => $roles,
            'volunteerDisplayOrderSlugs' => $volunteerDisplayOrderSlugs, // <-- NOW DEFINED
            'staffDisplayOrderSlugs' => $staffDisplayOrderSlugs, // <-- NOW DEFINED
            'limitedRoleCounts' => $limitedRoleCounts, // <-- NOW DEFINED
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
                    ->where(fn($query) => $query->whereNotNull('contact_number'))
            ],
            'email_address' => [
                'nullable',
                'email',
                Rule::unique('profiles')
                    ->ignore($profile->id, 'id')
                    ->where(fn($query) => $query->whereNotNull('email_address'))
            ],

            'birthday' => 'nullable|date',
            'gender' => ['required', Rule::in($gender_values)],
            'marital_status' => ['required', Rule::in($marital_status_values)],
            'joined_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'member_status' => ['required', Rule::in($member_status_values)],

            // ADD THIS VALIDATION FOR THE BASE ROLE SLUG
            'base_role' => [
                'nullable',
                'string',
                // Ensure the slug exists in the roles table
                'exists:roles,slug'
            ],

            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', Rule::in($role_ids)],

            'member_status' => ['required', 'string', Rule::in($member_status_values)],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update the profile with all validated data
            $profile->update(array_merge($validatedData, [
                'last_updated_by_id' => Auth::id(),
            ]));

            // 2. Handle Role Synchronization
            $status = $validatedData['member_status']; // Get the submitted status

            if ($status === MemberStatusEnum::INACTIVE->value) {
                // If Inactive, delete ALL roles (including 'member')
                $profile->roles()->sync([]);
            } else {
                // === FIX START: Correctly assemble all role IDs for Active members ===

                // 1. Get the mandatory 'member' role ID
                $memberRoleId = Role::where('slug', 'member')->value('id');

                // 2. Get the ID of the selected 'base_role' (staff or volunteer)
                // The input is a SLUG, so we look up the ID.
                $baseRoleSlug = $request->input('base_role');
                $baseRoleId = $baseRoleSlug ? Role::where('slug', $baseRoleSlug)->value('id') : null;

                // 3. Start the final role collection with the mandatory roles
                $finalRoleIds = collect([$memberRoleId, $baseRoleId]);

                // 4. Add the specific (Tier 2) roles (which are already IDs in $validatedData['roles'])
                $specificRoleIds = $validatedData['roles'] ?? [];
                $finalRoleIds = $finalRoleIds->merge($specificRoleIds);

                // Filter out nulls and ensure uniqueness
                $finalRoleIds = $finalRoleIds->filter()->unique();

                // *** The complex conflict resolution logic (if you have it) should go here ***
                // Your code snippet suggests complex filtering with $finalSlugs->reject(...),
                // which should use the $finalRoleIds you just prepared.

                // **For now, to fix the saving issue, we perform a simple sync:**
                // If you have conflict resolution logic, replace this line with your final conflict resolution and sync.
                // If the conflict logic is present and relies on a variable named $finalRoleIds, this preparation should fix the issue.
                $profile->roles()->sync($finalRoleIds->toArray());

                // === FIX END ===
            }

            $profile->save();

            DB::commit();

            return redirect()->route('profiles.profile', $profile->id)->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            $validatedData['last_updated_by_id'] = Auth::id();

            // Initialize roles to be synced
            $newRoleIds = [];
            $memberStatus = $validatedData['member_status']; // Capture status from request

            // 2. Custom Business Logic Validation (only if Active)
            if ($memberStatus === MemberStatusEnum::ACTIVE->value) {

                // Get 'Member' Role ID to include it in the sync list later (for Active profiles)
                $memberRole = Role::where('slug', 'member')->first();
                if ($memberRole) {
                    $newRoleIds[] = $memberRole->id;
                }

                $submittedRoleIds = $validatedData['roles'] ?? [];


                // Merge all submitted specific roles (Tier 2) and base role into the sync list for VALIDATION
                $newRoleIdsForValidation = array_merge($newRoleIds, $submittedRoleIds);

                // Re-fetch slugs for the specific roles for validation checks
                // IMPORTANT: Exclude the 'member' role from the validation slugs
                $submittedRoleSlugs = Role::whereIn('id', $newRoleIdsForValidation)
                    ->where('slug', '!=', 'member')
                    ->pluck('slug')->toArray();

                // The rest of the validation logic (A & B) goes here...

                $volunteerAllowedSlugs = [
                    'ministry_leader',
                    'ministry_assistant',
                    'pastor_in_training',
                    'team_member',
                ];
                $staffAllowedSlugs = [
                    'admin',
                    'ministry_leader',
                    'ministry_assistant',
                    'pastor_in_training',
                    'associate_pastor',
                    'board',
                    'lead_pastor',
                    'church_advisor',
                ];

                // Filter out base roles and 'member' from the slugs being checked against the allowed list
                $specificSlugsToCheck = array_diff($submittedRoleSlugs, ['staff', 'volunteer']);

                // B. Lead Pastor / Church Advisor Count and Mutual Exclusion Check
                $isLeadPastor = in_array('lead_pastor', $submittedRoleSlugs);
                $isChurchAdvisor = in_array('church_advisor', $submittedRoleSlugs);

                // B.1. Mutual Exclusion Check
                if ($isLeadPastor && $isChurchAdvisor) {
                    throw ValidationException::withMessages([
                        'roles' => 'A profile cannot be both a Lead Pastor and a Church Advisor.',
                    ]);
                }

                // Get IDs for Lead Pastor and Church Advisor roles (re-fetch them if the profile method wasn't called)
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

                // At this point, $newRoleIds contains 'member' role ID, base role ID, and Tier 2 role IDs.
                // We pass it to syncProfileRoles below.

            } else {
                // Status is INACTIVE. Clear $newRoleIds to prepare for full detach.
                $newRoleIds = [];
            }

            // 3. Proceed with update and role sync
            DB::transaction(function () use ($profile, $validatedData, $newRoleIds, $memberStatus) {
                $profile->update($validatedData);

                if ($memberStatus === MemberStatusEnum::ACTIVE->value) {
                    // For active, $newRoleIds contains 'member', base role, and specific roles (pre-validated)
                    $this->syncProfileRoles($profile, $newRoleIds);
                } else {
                    // For inactive, detach all roles.
                    $profile->roles()->sync([]); // <-- FIX: Explicitly detach all roles
                }
            });

            return redirect()->route('profiles.profile', $profile->id)->with('success', 'Profile updated successfully.');
        }
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
            $allOtherSlugs = $finalSlugs->reject(
                fn($slug) =>
                $slug === 'church_advisor' || $slug === 'member'
            )->toArray();

            $rolesToRemoveSlugs = array_merge($rolesToRemoveSlugs, $allOtherSlugs);
        }

        // Existing Rule: Staff/Board Conflict (preventing being a 'volunteer')
        if (($finalSlugs->contains('staff') || $finalSlugs->contains('board')) && !$finalSlugs->contains('lead_pastor')) {
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
