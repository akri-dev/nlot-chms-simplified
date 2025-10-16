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
                            value="{{ old('first_name', $profile->first_name) }}" required disabled>
                        @error('first_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="middle-name" class="mb-1">Middle Name (if Applicable)</label>
                        <input type="text" class="form-control" id="middle-name" name="middle_name"
                            value="{{ old('middle_name', $profile->middle_name) }}" disabled>
                        @error('middle_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="last-name" class="mb-1">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last_name"
                            value="{{ old('last_name', $profile->last_name) }}" required disabled>
                        @error('last_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-8">
                        <label for="street-address" class="mb-1">Street Address</label>
                        <input type="text" class="form-control" id="street-address" name="street_address"
                            value="{{ old('street_address', $profile->street_address) }}" disabled>
                        @error('street_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="city-address" class="mb-1">City</label>
                        <input type="text" class="form-control" id="city-address" name="city_address"
                            value="{{ old('city_address', $profile->city_address) }}" disabled>
                        @error('city_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-3">
                        <label for="contact-number" class="mb-1">Contact Number</label>
                        <input type="tel" class="form-control" id="contact-number" name="contact_number"
                            value="{{ old('contact_number', $profile->contact_number) }}" disabled>
                        @error('contact_number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-4">
                        <label for="email-address" class="mb-1">Email Address</label>
                        <input type="email" class="form-control" id="email-address" name="email_address"
                            value="{{ old('email', $profile->email) }}" disabled>
                        @error('email_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        <label for="birthday" class="mb-1">Date of Birth</label>
                        <input type="text" class="form-control calendar-picker" id="birthday" name="birthday"
                            value="{{ old('birthday', $profile->formatted_birthday) }}" disabled>
                        @error('birthday')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-2">
                        <p class="mb-1 d-block">Gender</p>
                        <input class="btn-check text-dark " type="radio" name="gender" id="btn-check-male-outlined"
                            value="Male" autocomplete="off"
                            {{ old('gender', $profile->gender->value ?? $profile->gender) == 'Male' ? 'checked' : '' }}
                            disabled>
                        <label class="btn btn-outline-secondary d-inline-block px-4 mb-1 mb-lg-0"
                            for="btn-check-male-outlined">Male</label>
                        <input class="btn-check text-dark" type="radio" name="gender" id="btn-check-female-outlined"
                            value="Female" autocomplete="off"
                            {{ old('gender', $profile->gender->value ?? $profile->gender) == 'Female' ? 'checked' : '' }}
                            disabled>
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
                                // If $profile->marital_status is an Enum object, use its value.
                                // If it's already a string (which happens when old() returns a value), use it directly.
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
                            value="{{ old('joined_date', $profile->formatted_joined_date) }}" disabled>
                        @error('joined_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        <label for="baptism-date">Date of Baptism (if Applicable)</label>
                        <input type="text" class="form-control calendar-picker" id="baptism-date" name="baptism_date"
                            value="{{ old('baptism_date', $profile->formatted_baptism_date) }}" disabled>
                        @error('baptism_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-3">
                        {{-- Action buttons container --}}
                        <div id="action-buttons-container" class="d-flex justify-content-end align-items-end mt-4">
                            <button type="button" id="edit-button" class="btn btn-warning ms-3">
                                <i class="fa-solid fa-pen"></i> Edit Profile
                            </button>
                            <div id="edit-mode-buttons">
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

            // Select all editable form controls
            const editableInputs = form.querySelectorAll('input, select');

            // Function to enable/disable the form fields and toggle buttons
            function setEditMode(isEditing) {
                // 1. Toggle input disability: Enable all fields if editing, disable all fields if viewing/cancelling.
                editableInputs.forEach(input => {
                    // Hidden fields (like _token, _method) should be skipped
                    if (input.type !== 'hidden') {
                        input.disabled = !isEditing;
                        // Special handling for radio/checkbox groups like Gender
                        if (input.classList.contains('btn-check')) {
                            // The associated label's appearance will update automatically via CSS
                        }
                    }
                });

                // 2. Toggle buttons visibility
                if (isEditing) {
                    editButton.style.display = 'none'; // Hide edit button
                    editModeButtons.style.display = 'flex'; // Show cancel and save buttons
                } else {
                    // 3. Reset form to original values when cancelling
                    form.reset();

                    editButton.style.display = 'block'; // Show edit button
                    editModeButtons.style.display = 'none'; // Hide cancel and save buttons

                    // Re-run disable logic explicitly after reset to ensure all fields are disabled
                    // (form.reset() only resets values, not the `disabled` attribute if it was set dynamically)
                    editableInputs.forEach(input => {
                        if (input.type !== 'hidden') {
                            input.disabled = true;
                        }
                    });
                }
            }

            // Set initial state for editModeButtons if inline style fails or is removed
            editModeButtons.style.display = 'none';

            // Event listeners
            editButton.addEventListener('click', () => setEditMode(true));
            cancelEditButton.addEventListener('click', () => setEditMode(false));
        });
    </script>
@endpush
