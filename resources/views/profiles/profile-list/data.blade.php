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
                            value="{{ old('street_address', $profile->street_address) }}" placeholder="e.g. 123 Gonzales St." disabled>
                        @error('street_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="city-address" class="mb-1">City</label>
                        <input type="text" class="form-control" id="city-address" name="city_address"
                            value="{{ old('city_address', $profile->city_address) }}" placeholder="e.g. Tagaytay City" disabled>
                        @error('city_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-3">
                        <label for="contact-number" class="mb-1">Contact Number</label>
                        <input type="tel" class="form-control" id="contact-number" name="contact_number"
                            value="{{ old('contact_number', $profile->contact_number) }}" placeholder="e.g. 0912 345 6789" disabled>
                        @error('contact_number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="email-address" class="mb-1">Email Address</label>
                        <input type="email" class="form-control" id="email-address" name="email_address"
                            value="{{ old('email_address', $profile->email_address) }}" placeholder="e.g. mail@gmail.com" disabled>
                        @error('email_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        <label for="birthday" class="mb-1">Date of Birth</label>
                        <input type="text" class="form-control calendar-picker" id="birthday" name="birthday"
                            value="{{ old('birthday', $profile->formatted_birthday  ) }}" placeholder="Select Date of Birth" disabled>
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
                            value="{{ old('joined_date', $profile->formatted_joined_date) }}" placeholder="Select Date of First Visit" disabled>
                        @error('joined_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        <label for="baptism-date">Date of Baptism (if Applicable)</label>
                        <input type="text" class="form-control calendar-picker" id="baptism-date" name="baptism_date"
                            value="{{ old('baptism_date', $profile->formatted_baptism_date) }}" placeholder="Select Date of Baptism" disabled>
                        @error('baptism_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- START: Membership Status and Role Selection --}}
                    <div class="form-group col-3">
                        <label for="member-status">Membership Status</label>
                        <select class="form-select" id="member-status" aria-label="Member Status"
                            name="member_status" required disabled>
                            @php
                                use App\Enums\MemberStatusEnum;
                                $profileMemberStatus = $profile->member_status->value ?? $profile->member_status;
                                $memberStatus = old('member_status', $profileMemberStatus);
                            @endphp
                            @foreach (MemberStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}" {{ $memberStatus == $status->value ? 'selected' : '' }}>
                                    {{ $status->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('member_status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label class="mb-2"><strong>Assigned Roles</strong></label>
                        <div class="border p-3 rounded bg-light" id="roles-container">
                            @php
                                // Get current role IDs for pre-checking
                                $currentRoleIds = $profile->roles->pluck('id')->toArray();
                            @endphp

                            @forelse ($roles as $role)
                                @php
                                    // 'Member' role is special: un-editable but should be checked if assigned.
                                    $isMemberRole = $role->slug === 'member';
                                    $isChecked = in_array($role->id, $currentRoleIds) || old('roles') && in_array($role->id, old('roles'));
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                        id="role-{{ $role->id }}" value="{{ $role->id }}"
                                        {{ $isChecked ? 'checked' : '' }}
                                        {{ $isMemberRole ? 'disabled' : '' }} {{-- 'Member' is disabled for edit --}}
                                        disabled>
                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                        {{ $role->name }}
                                        @if ($isMemberRole)
                                            <small class="text-muted">(Default)</small>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted">No roles defined.</p>
                            @endforelse
                            @error('roles')
                                <div class="text-danger small d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- END: Membership Status and Role Selection --}}

                <div class="row">
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

            // Select all editable form controls (including new role checkboxes)
            const editableInputs = form.querySelectorAll('input, select, textarea');
            // Select the special 'Member' role checkbox to keep it disabled
            // Assuming $roles contains roles and we get the ID from the Blade context passed by the controller.
            const memberRoleCheckbox = document.querySelector('input[name="roles[]"][disabled]'); 

            // Function to enable/disable the form fields and toggle buttons
            function setEditMode(isEditing) {
                // 1. Toggle input disability
                editableInputs.forEach(input => {
                    // Hidden fields (like _token, _method) should be skipped
                    if (input.type !== 'hidden') {
                        input.disabled = !isEditing;
                    }
                });

                // 2. Enforce 'Member' role disability if it exists
                if (memberRoleCheckbox) {
                    memberRoleCheckbox.disabled = true;
                }

                // 3. Toggle buttons visibility
                if (isEditing) {
                    editButton.style.display = 'none'; // Hide edit button
                    editModeButtons.style.display = 'flex'; // Show cancel and save buttons
                } else {
                    // 4. Reset form to original values when cancelling
                    form.reset();

                    editButton.style.display = 'block'; // Show edit button
                    editModeButtons.style.display = 'none'; // Hide cancel and save buttons

                    // 5. Re-run disable logic explicitly after reset to ensure all fields are disabled
                    editableInputs.forEach(input => {
                        if (input.type !== 'hidden') {
                            input.disabled = true;
                        }
                    });
                    // Re-enforce 'Member' role disability
                    if (memberRoleCheckbox) {
                        memberRoleCheckbox.disabled = true;
                    }
                }
            }

            // Set initial state for editModeButtons
            editModeButtons.style.display = 'none';

            // Event listeners
            editButton.addEventListener('click', () => setEditMode(true));
            cancelEditButton.addEventListener('click', () => setEditMode(false));
        });
    </script>
@endpush