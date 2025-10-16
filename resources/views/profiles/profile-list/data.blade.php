@extends('layouts.app')

@section('title', 'Profile: ' . $profile->first_name . ' ' . $profile->last_name)

@section('content')
    <div class="row">
        <div class="col d-flex justify-content-start align-items-center">
            <h1>Profile: {{ $profile->first_name }} {{ $profile->last_name }}</h1>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form id="profile-form" action="{{ route('profiles.profile.update', $profile->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row mb-2">
                    <div class="form-group col-4 ">
                        <label for="first-name" class="mb-1">First Name</label>
                        <input type="text" class="form-control" id="first-name" name="first_name"
                            value="{{ old('first_name', $profile->first_name) }}" placeholder="e.g. John" required disabled>
                        @error('first_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="middle-name" class="mb-1">Middle Name (if Applicable)</label>
                        <input type="text" class="form-control" id="middle-name" name="middle_name"
                            value="{{ old('middle_name', $profile->middle_name) }}" placeholder="e.g. Jonah" disabled>
                        @error('middle_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="last-name" class="mb-1">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last_name"
                            value="{{ old('last_name', $profile->last_name) }}" placeholder="e.g. Smith" required disabled>
                        @error('last_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-8">
                        <label for="street-address" class="mb-1">Street Address</label>
                        <input type="text" class="form-control" id="street-address" name="street_address"
                            value="{{ old('street_address', $profile->street_address) }}"
                            placeholder="e.g. 123 Gonzales St." disabled>
                        @error('street_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="city-address" class="mb-1">City</label>
                        <input type="text" class="form-control" id="city-address" name="city_address"
                            value="{{ old('city_address', $profile->city_address) }}" placeholder="e.g. Tagaytay City"
                            disabled>
                        @error('city_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-3">
                        <label for="contact-number" class="mb-1">Contact Number</label>
                        <input type="tel" class="form-control" id="contact-number" name="contact_number"
                            value="{{ old('contact_number', $profile->contact_number) }}" placeholder="e.g. 0912 345 6789"
                            disabled>
                        @error('contact_number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="email-address" class="mb-1">Email Address</label>
                        <input type="email" class="form-control" id="email-address" name="email_address"
                            value="{{ old('email_address', $profile->email_address) }}" placeholder="e.g. mail@gmail.com"
                            disabled>
                        @error('email_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        <label for="birthday" class="mb-1">Date of Birth</label>
                        <input type="text" class="form-control calendar-picker" id="birthday" name="birthday"
                            value="{{ old('birthday', $profile->formatted_birthday) }}" placeholder="Select Date of Birth"
                            disabled>
                        @error('birthday')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-2">
                        <p class="mb-1 d-block">Gender</p>
                        <input class="btn-check text-dark " type="radio" name="gender" id="btn-check-male-outlined"
                            value="Male" autocomplete="off"
                            {{ old('gender', $profile->gender->value ?? $profile->gender) == 'Male' ? 'checked' : '' }}
                            required disabled>
                        <label class="btn btn-outline-secondary d-inline-block px-4 mb-1 mb-lg-0"
                            for="btn-check-male-outlined">Male</label>
                        <input class="btn-check text-dark" type="radio" name="gender" id="btn-check-female-outlined"
                            value="Female" autocomplete="off"
                            {{ old('gender', $profile->gender->value ?? $profile->gender) == 'Female' ? 'checked' : '' }}
                            required disabled>
                        <label class="btn btn-outline-secondary d-inline-block px-3"
                            for="btn-check-female-outlined">Female</label>
                        @error('gender')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    {{-- Retained original fields from your file for context --}}
                    {{-- ... (Marital Status, Joined Date, Baptism Date) ... --}}
                    <div class="form-group col-3">
                        <label for="marital-status">Marital Status</label>
                        <select class="form-select" id="marital-status" aria-label="Marital Status"
                            name="marital_status" required disabled>
                            <option value="" hidden>Select Marital Status</option>
                            @php
                                $profileStatusValue = $profile->marital_status->value ?? $profile->marital_status;
                                $maritalStatus = old('marital_status', $profileStatusValue);
                            @endphp
                            <option value="Single" {{ $maritalStatus == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ $maritalStatus == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ $maritalStatus == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="Divorced/Separated/Annulled"
                                {{ $maritalStatus == 'Divorced/Separated/Annulled' ? 'selected' : '' }}>
                                Divorced/Separated/Annulled</option>
                        </select>
                        @error('marital_status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        <label for="joined-date">Date of First Visit</label>
                        <input type="text" class="form-control calendar-picker" id="joined-date" name="joined_date"
                            value="{{ old('joined_date', $profile->formatted_joined_date) }}"
                            placeholder="Select Date of First Visit" disabled>
                        @error('joined_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        <label for="baptism-date">Date of Baptism (if Applicable)</label>
                        <input type="text" class="form-control calendar-picker" id="baptism-date" name="baptism_date"
                            value="{{ old('baptism_date', $profile->formatted_baptism_date) }}"
                            placeholder="Select Date of Baptism" disabled>
                        @error('baptism_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="mt-4 mb-4">

                {{-- START: New Conditional Membership Status and Roles Section --}}

                {{-- Membership Status (1) and Base Role (2) in ONE ROW --}}
                <div class="row mb-4">

                    {{-- COLUMN 1: 1. Membership Status --}}
                    <div class="col-md-3">
                        <label class="mb-2 d-block"><strong>1. Membership Status</strong></label>
                        {{-- Hidden input for the actual status value --}}
                        <input type="hidden" name="member_status" id="member-status-hidden"
                            value="{{ old('member_status', $profile->member_status->value) }}">

                        <div id="member-status-container" class="d-inline-block">
                            @php
                                use App\Enums\MemberStatusEnum;
                                $currentStatus = old('member_status', $profile->member_status->value);
                            @endphp

                            {{-- Active/Member Button --}}
                            <input class="btn-check" type="radio" name="member_status_radio" id="status-active"
                                value="{{ MemberStatusEnum::ACTIVE->value }}" autocomplete="off"
                                {{ $currentStatus == MemberStatusEnum::ACTIVE->value ? 'checked' : '' }} disabled>
                            <label class="btn btn-outline-success px-4" for="status-active" data-status-label="true">
                                Member
                            </label>

                            {{-- Inactive Button --}}
                            <input class="btn-check" type="radio" name="member_status_radio" id="status-inactive"
                                value="{{ MemberStatusEnum::INACTIVE->value }}" autocomplete="off"
                                {{ $currentStatus == MemberStatusEnum::INACTIVE->value ? 'checked' : '' }} disabled>
                            <label class="btn btn-outline-danger px-4" for="status-inactive" data-status-label="true">
                                Inactive
                            </label>
                        </div>
                        @error('member_status')
                            <div class="text-danger small d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- COLUMN 2: 2. Base Role (Mutually Exclusive) --}}
                    <div class="col-md-3" id="roles-assignment-container"
                        style="display: {{ $currentStatus == MemberStatusEnum::ACTIVE->value ? 'block' : 'none' }};">
                        <label class="mb-2 d-block"><strong>2. Base Role (Mutually Exclusive)</strong></label>

                        @php
                            // Determine the current base role (Staff or Volunteer)
                            $currentBaseRole = $profile->roles
                                ->pluck('slug')
                                ->intersect(['staff', 'volunteer'])
                                ->first();
                            $currentBaseRole = old('base_role', $currentBaseRole);
                        @endphp

                        {{-- Hidden input for the required base role (Staff or Volunteer) --}}
                        <input type="hidden" name="base_role" id="base-role-hidden" value="{{ $currentBaseRole }}">

                        <div id="base-role-container" class="d-inline-block">
                            {{-- Volunteer Button --}}
                            <input class="btn-check base-role-check" type="radio" name="base_role_radio"
                                id="base-role-volunteer" value="volunteer" autocomplete="off"
                                {{ $currentBaseRole == 'volunteer' ? 'checked' : '' }} disabled>
                            <label class="btn btn-outline-primary px-4" for="base-role-volunteer">Volunteer</label>

                            {{-- Staff Button --}}
                            <input class="btn-check base-role-check" type="radio" name="base_role_radio"
                                id="base-role-staff" value="staff" autocomplete="off"
                                {{ $currentBaseRole == 'staff' ? 'checked' : '' }} disabled>
                            <label class="btn btn-outline-primary px-4" for="base-role-staff">Staff</label>
                        </div>
                        @error('base_role')
                            <div class="text-danger small d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Reactivate Container moved to its own row/column for correct layout behavior --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div id="reactivate-container"
                            style="display: {{ $currentStatus == MemberStatusEnum::INACTIVE->value ? 'block' : 'none' }};">
                            <button type="button" id="reactivate-button" class="btn btn-success px-4" disabled>
                                Reactivate Membership
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Conditional Role Assignment Container (Tier 2 Roles section begins here) --}}
                <div id="tier-2-roles-container-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <label class="mb-2 d-block"><strong>3. Specific Roles (Select Multiple)</strong></label>
                            <div id="tier-2-roles-container" class="border p-3 rounded bg-light">
                                @php
                                    // --- REFACTORED SLUG DEFINITIONS FOR DISPLAY ORDER ---

                                    // Volunteer Display Order
                                    $volunteerDisplayOrderSlugs = [
                                        'ministry_leader', // Ministry Head
                                        'ministry_assistant',
                                        'pastor_in_training',
                                        'team_member',
                                    ];

                                    // Staff Display Order (Requested Order)
                                    $staffDisplayOrderSlugs = [
                                        'admin',
                                        'ministry_leader', // Ministry Head
                                        'ministry_assistant',
                                        'pastor_in_training',
                                        'associate_pastor',
                                        'board',
                                        'lead_pastor',
                                        'church_advisor',
                                    ];

                                    // Filter all roles that are NOT base/member roles, and convert to an array keyed by slug for easy lookup.
                                    $allTier2Roles = $roles
                                        ->filter(fn($role) => !in_array($role->slug, ['member', 'staff', 'volunteer']))
                                        ->keyBy('slug');

                                    // Get current assigned roles excluding base and member
                                    $currentTier2RoleIds = $profile->roles
                                        ->filter(fn($role) => !in_array($role->slug, ['member', 'staff', 'volunteer']))
                                        ->pluck('id')
                                        ->toArray();

                                    // Helper to get roles in a specific order
                                    $getOrderedRoles = fn(array $slugs) => collect($slugs)
                                        ->map(fn($slug) => $allTier2Roles->get($slug))
                                        ->filter(); // Remove any roles that don't exist
                                @endphp

                                <p id="role-type-message" class="text-muted small">Select a Base Role (Staff or Volunteer)
                                    above to view applicable specific roles.</p>

                                {{-- VOLUNTEER ROLES --}}
                                <div id="volunteer-roles" class="role-group" style="display: none;">
                                    <label class="d-block mb-1">Volunteer Specific Roles:</label>
                                    @foreach ($getOrderedRoles($volunteerDisplayOrderSlugs) as $role)
                                        @php
                                            $isChecked =
                                                in_array($role->id, $currentTier2RoleIds) ||
                                                (old('roles') && in_array($role->id, old('roles')));
                                            $displayName =
                                                $role->slug === 'ministry_leader' ? 'Ministry Head' : $role->name;
                                        @endphp
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input tier-2-role" type="checkbox" name="roles[]"
                                                id="role-{{ $role->id }}" value="{{ $role->id }}"
                                                {{ $isChecked ? 'checked' : '' }} disabled>
                                            <label class="form-check-label" for="role-{{ $role->id }}">
                                                {{ $displayName }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- STAFF ROLES (New Order) --}}
                                <div id="staff-roles" class="role-group" style="display: none;">
                                    <label class="d-block mb-1">Staff Specific Roles:</label>
                                    @foreach ($getOrderedRoles($staffDisplayOrderSlugs) as $role)
                                        @php
                                            $isChecked =
                                                in_array($role->id, $currentTier2RoleIds) ||
                                                (old('roles') && in_array($role->id, old('roles')));
                                            $displayName =
                                                $role->slug === 'ministry_leader' ? 'Ministry Head' : $role->name;
                                        @endphp
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input tier-2-role" type="checkbox" name="roles[]"
                                                id="role-{{ $role->id }}" value="{{ $role->id }}"
                                                {{ $isChecked ? 'checked' : '' }} disabled>
                                            <label class="form-check-label" for="role-{{ $role->id }}">
                                                {{ $displayName }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('roles')
                                <div class="text-danger small d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- END: New Conditional Membership Status and Roles Section --}}

                {{-- ... (Existing Action buttons container) ... --}}
                <div class="row mt-4">
                    <div class="col-12">
                        {{-- Action buttons container --}}
                        <div id="action-buttons-container" class="d-flex justify-content-end align-items-end">
                            <button type="button" id="edit-button" class="btn btn-warning ms-3">
                                <i class="fa-solid fa-pen"></i> Edit Profile
                            </button>
                            <div id="edit-mode-buttons" style="display: none;">
                                <div class="me-2 d-inline-block">
                                    <button type="button" id="cancel-edit-button" class="btn btn-outline-danger px-4">
                                        <i class="fa-solid fa-x"></i> Cancel
                                    </button>
                                </div>
                                <div class="d-inline-block">
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="fa-solid fa-check"></i> Save Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('profile-form');
            const editButton = document.getElementById('edit-button');
            const cancelEditButton = document.getElementById('cancel-edit-button');
            const editModeButtons = document.getElementById('edit-mode-buttons');
            const statusContainer = document.getElementById('member-status-container');
            const rolesAssignmentContainer = document.getElementById('roles-assignment-container');
            const reactivateContainer = document.getElementById('reactivate-container');
            const reactivateButton = document.getElementById('reactivate-button');
            const memberStatusHidden = document.getElementById('member-status-hidden');
            const baseRoleHidden = document.getElementById('base-role-hidden');
            const baseRoleChecks = form.querySelectorAll('.base-role-check');
            const volunteerRolesContainer = document.getElementById('volunteer-roles');
            const staffRolesContainer = document.getElementById('staff-roles');
            const tier2RoleCheckboxes = form.querySelectorAll('.tier-2-role');
            const roleTypeMessage = document.getElementById('role-type-message');

            // Select all editable form controls
            const profileInputs = form.querySelectorAll('input, select, textarea');
            const editableInputs = Array.from(profileInputs).filter(input => !input.dataset.statusLabel && !input.classList.contains('base-role-check') && !input.classList.contains('tier-2-role'));

            // Define initial variables from Blade (data from the database)
            const initialStatus = memberStatusHidden.value;
            const initialBaseRole = baseRoleHidden.value;
            const initialRoleIds = @json(isset($currentTier2RoleIds) ? $currentTier2RoleIds : []);

            // Data passed from controller for disabling limited roles
            const initialLimitedRoleCounts = @json($limitedRoleCounts ?? ['lead_pastor' => 0, 'church_advisor' => 0]);

            // IDs for logic checks
            const leadPastorRoleId = @json(\App\Models\Role::where('slug', 'lead_pastor')->value('id') ?? 0);
            const churchAdvisorRoleId = @json(\App\Models\Role::where('slug', 'church_advisor')->value('id') ?? 0);
            const ministryAssistantRoleId = @json(\App\Models\Role::where('slug', 'ministry_assistant')->value('id') ?? 0); 
            const associatePastorRoleId = @json(\App\Models\Role::where('slug', 'associate_pastor')->value('id') ?? 0);

            const MAX_LIMIT = 2;

            // --- Helper Functions ---

            // Sets the CHECKED state for roles in a specific container based on database values (initialRoleIds)
            function setCheckedStateForContainer(container) {
                if (!container) return;
                container.querySelectorAll('.tier-2-role').forEach(input => {
                    const roleId = parseInt(input.value);
                    if (initialRoleIds.includes(roleId)) {
                        input.checked = true;
                    } else {
                        input.checked = false;
                    }
                });
            }

            // Helper to clear, disable, and remove name from a specific container (used for the UNSELECTED group)
            function clearUnselectedContainer(container) {
                if (!container) return;
                container.querySelectorAll('.tier-2-role').forEach(input => {
                    input.checked = false;
                    input.disabled = true;
                    input.removeAttribute('name');
                    container.style.display = 'none';
                });
            }

            // Clears only the checked state for the currently visible container (for a FRESH START)
            function clearCheckedState(container) {
                if (!container) return;
                container.querySelectorAll('.tier-2-role').forEach(input => {
                    input.checked = false;
                });
            }

            function toggleRolesAssignment(status) {
                if (status === 'Active') {
                    rolesAssignmentContainer.style.display = 'block';
                    reactivateContainer.style.display = 'none';
                } else {
                    rolesAssignmentContainer.style.display = 'none';
                    reactivateContainer.style.display = 'block';

                    baseRoleChecks.forEach(input => input.checked = false);
                    baseRoleHidden.value = '';

                    clearUnselectedContainer(volunteerRolesContainer);
                    clearUnselectedContainer(staffRolesContainer);

                    updateRoleSelectionDisplay(null);
                }
            }

            // Manages visibility, DISABLED state, and NAME attribute state for the SELECTED container
            function updateRoleSelectionDisplay(baseRole) {
                const isEditing = form.dataset.isEditing === 'true';

                roleTypeMessage.style.display = 'none';

                let targetContainer = null;
                let unselectedContainer = null;

                if (baseRole === 'volunteer') {
                    targetContainer = volunteerRolesContainer;
                    unselectedContainer = staffRolesContainer;
                } else if (baseRole === 'staff') {
                    targetContainer = staffRolesContainer;
                    unselectedContainer = volunteerRolesContainer;
                }

                // Ensure the unselected container is hidden and cleared
                clearUnselectedContainer(unselectedContainer);

                if (targetContainer) {
                    targetContainer.style.display = 'block';

                    // --- CONFLICT LOGIC SETUP ---
                    // Get the IDs of the roles currently CHECKED by the user in the form
                    const currentlyCheckedRoleIds = Array.from(form.querySelectorAll('.tier-2-role:checked'))
                        .map(input => parseInt(input.value));

                    const isLeadPastorSelected = currentlyCheckedRoleIds.includes(leadPastorRoleId);
                    const isChurchAdvisorSelected = currentlyCheckedRoleIds.includes(churchAdvisorRoleId);

                    // Roles that conflict with Lead Pastor
                    const leadPastorConflictIds = [
                        ministryAssistantRoleId,
                        associatePastorRoleId,
                    ];

                    // Check if any of the Lead Pastor conflict roles are selected
                    const isLeadPastorConflictRoleSelected = currentlyCheckedRoleIds.some(id => leadPastorConflictIds.includes(id));

                    // Check if any non-advisor role is selected (for Church Advisor conflict)
                    const isNonAdvisorRoleSelected = currentlyCheckedRoleIds.some(id => id !== churchAdvisorRoleId);
                    // --- END CONFLICT LOGIC SETUP ---


                    targetContainer.querySelectorAll('.tier-2-role').forEach(input => {
                        const roleId = parseInt(input.value);
                        let isDisabledByLimit = false;
                        let isDisabledByConflict = false;

                        // Restore name attribute if editing
                        if (isEditing) {
                            input.setAttribute('name', 'roles[]');
                        } else {
                            input.removeAttribute('name'); // Remove name in read-only mode
                        }

                        // 1. Check for exclusivity conflict (Runs first as it depends on current selection)
                        if (isEditing) {
                            // Rule B: Church Advisor Conflict (Most restrictive)
                            if (isChurchAdvisorSelected && roleId !== churchAdvisorRoleId) {
                                isDisabledByConflict = true;
                            } else if (roleId === churchAdvisorRoleId && isNonAdvisorRoleSelected) {
                                isDisabledByConflict = true;
                            }

                            // Rule A: Lead Pastor Exclusivity 
                            if (!isDisabledByConflict) {
                                if (roleId === leadPastorRoleId && isLeadPastorConflictRoleSelected) {
                                    isDisabledByConflict = true;
                                } else if (leadPastorConflictIds.includes(roleId) && isLeadPastorSelected) {
                                    isDisabledByConflict = true;
                                }
                            }
                        }

                        // ⭐ 2. Check for Lead Pastor/Church Advisor max limit (This check applies the PERMANENT DISABLE requested)
                        if (roleId === leadPastorRoleId || roleId === churchAdvisorRoleId) {
                            const roleSlug = (roleId === leadPastorRoleId) ? 'lead_pastor' : 'church_advisor';

                            const currentProfileHasRole = initialRoleIds.includes(roleId);
                            const othersCount = initialLimitedRoleCounts[roleSlug];

                            // If limit is reached AND current profile doesn't have the role, disable it.
                            if (othersCount >= MAX_LIMIT && !currentProfileHasRole) {
                                isDisabledByLimit = true;
                            }
                        }

                        // Final disabled state: Disabled if NOT editing OR disabled by limit OR disabled by conflict
                        input.disabled = !isEditing || isDisabledByLimit || isDisabledByConflict;

                        // Uncheck on conflict if the profile doesn't already have it
                        if (input.checked && (isDisabledByLimit || isDisabledByConflict) && !initialRoleIds.includes(
                                roleId)) {
                            input.checked = false;
                        }
                    });
                } else {
                    roleTypeMessage.style.display = 'block';
                }
            }

            // --- Edit Mode Toggle (setEditMode) remains unchanged ---
            function setEditMode(isEditing) {
                const currentStatus = memberStatusHidden.value;
                const currentBaseRole = baseRoleHidden.value;

                form.dataset.isEditing = isEditing;

                // 1. Toggle basic profile inputs
                editableInputs.forEach(input => {
                    input.disabled = !isEditing;
                });

                // 2. Toggle Membership Status buttons & Reactivate button
                statusContainer.querySelectorAll('input').forEach(input => input.disabled = !isEditing);
                reactivateButton.disabled = !isEditing;

                // 3. Toggle Base Roles buttons
                baseRoleChecks.forEach(input => input.disabled = !isEditing || currentStatus === 'Inactive');

                // 4. Initial check restoration on Edit ON 
                if (isEditing && currentStatus === 'Active') {
                    let selectedContainer = currentBaseRole === 'volunteer' ? volunteerRolesContainer : staffRolesContainer;
                    let unselectedContainer = currentBaseRole === 'volunteer' ? staffRolesContainer : volunteerRolesContainer;

                    // Restore checks only for the currently selected base role
                    setCheckedStateForContainer(selectedContainer);
                    // Clear and hide the unselected one
                    clearUnselectedContainer(unselectedContainer);
                } else {
                    // Status is Inactive, clear everything
                    clearUnselectedContainer(volunteerRolesContainer);
                    clearUnselectedContainer(staffRolesContainer);
                }

                // Call display update to apply conflict/limit disabling
                updateRoleSelectionDisplay(currentStatus === 'Active' ? currentBaseRole : null);

                // 5. Toggle buttons visibility
                if (isEditing) {
                    editButton.style.display = 'none';
                    editModeButtons.style.display = 'flex';
                } else {
                    // --- READ-ONLY MODE / CANCEL LOGIC ---
                    form.reset();
                    editButton.style.display = 'block';
                    editModeButtons.style.display = 'none';

                    // Restore initial values
                    document.getElementById('status-' + initialStatus.toLowerCase()).checked = true;
                    memberStatusHidden.value = initialStatus;

                    if (initialBaseRole) {
                        document.getElementById('base-role-' + initialBaseRole).checked = true;
                        baseRoleHidden.value = initialBaseRole;
                    }

                    // Restore the correct visibility and checking the correct boxes based on initial data
                    toggleRolesAssignment(initialStatus);

                    let finalSelectedContainer = initialBaseRole === 'volunteer' ? volunteerRolesContainer : staffRolesContainer;
                    let finalUnselectedContainer = initialBaseRole === 'volunteer' ? staffRolesContainer : volunteerRolesContainer;

                    clearUnselectedContainer(finalUnselectedContainer);
                    setCheckedStateForContainer(finalSelectedContainer);
                    
                    // Re-run display logic to apply limits and show current state
                    updateRoleSelectionDisplay(initialBaseRole);

                    editableInputs.forEach(input => input.disabled = true);
                }
            }


            // --- Event Handlers ---
            statusContainer.addEventListener('change', (event) => {
                if (event.target.name === 'member_status_radio') {
                    const status = event.target.value;
                    memberStatusHidden.value = status;
                    toggleRolesAssignment(status);

                    const isEditing = form.dataset.isEditing === 'true';
                    baseRoleChecks.forEach(input => input.disabled = !isEditing || status === 'Inactive');
                }
            });

            reactivateButton.addEventListener('click', () => {
                const activeRadio = document.getElementById('status-active');
                if (activeRadio) {
                    activeRadio.checked = true;
                    memberStatusHidden.value = 'Active';
                    toggleRolesAssignment('Active');

                    baseRoleChecks.forEach(input => input.disabled = false);
                }
            });

            // Logic on Base Role Change for fresh selection
            baseRoleChecks.forEach(checkbox => {
                checkbox.addEventListener('change', (event) => {
                    const baseRole = event.target.value;
                    baseRoleHidden.value = baseRole;

                    let unselectedContainer;
                    let selectedContainer;

                    if (baseRole === 'volunteer') {
                        unselectedContainer = staffRolesContainer;
                        selectedContainer = volunteerRolesContainer;
                    } else if (baseRole === 'staff') {
                        unselectedContainer = volunteerRolesContainer;
                        selectedContainer = staffRolesContainer;
                    }

                    // 1. Clear the UNSELECTED container
                    clearUnselectedContainer(unselectedContainer);

                    // 2. Clear the checked state for the currently visible/selected group to ensure a FRESH start
                    clearCheckedState(selectedContainer);

                    // 3. Run display logic: This REAPPLIES the permanent limit disability
                    updateRoleSelectionDisplay(baseRole);
                });
            });

            // Add event listener to re-evaluate disabled state whenever a Tier 2 role changes (for conflict checks)
            tier2RoleCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const currentBaseRole = baseRoleHidden.value;
                    updateRoleSelectionDisplay(currentBaseRole);
                });
            });


            // --- Initialization ---

            // 1. Set initial checked state based on initial profile data (only for the initial base role)
            let initialSelectedContainer = initialBaseRole === 'volunteer' ? volunteerRolesContainer : staffRolesContainer;
            setCheckedStateForContainer(initialSelectedContainer);

            // 2. Set initial disabled and visible state
            editModeButtons.style.display = 'none';
            setEditMode(false);

            // Event listeners
            editButton.addEventListener('click', () => setEditMode(true));
            cancelEditButton.addEventListener('click', () => setEditMode(false));
        });
    </script>
@endpush