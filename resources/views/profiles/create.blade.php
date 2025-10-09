@extends('layouts.app')

@section('title', 'Add Profile')

@section('content')
    <div class="row">
        <div class="col d-flex justify-content-start align-items-center">
            <h1>Add Profile</h1>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="">
                <div class="row mb-2">
                    <div class="form-group col-4 ">
                        <label for="first-name" class="mb-1">First Name</label>
                        <input type="text" class="form-control" id="first-name" name="first_name" placeholder="e.g. John"
                            required>
                    </div>
                    <div class="form-group col-4">
                        <label for="middle-name" class="mb-1">Middle Name (if Applicable)</label>
                        <input type="text" class="form-control" id="middle-name" name="middle_name"
                            placeholder="e.g. Jonah">
                    </div>
                    <div class="form-group col-4">
                        <label for="last-name" class="mb-1">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last_name" placeholder="e.g. Smith"
                            required>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-8">
                        <label for="street-address" class="mb-1">Street Address</label>
                        <input type="text" class="form-control" id="street-address" name="street_address"
                            placeholder="e.g. 123 Gonzales St.">
                    </div>
                    <div class="form-group col-4">
                        <label for="city-address" class="mb-1">City</label>
                        <input type="text" class="form-control" id="city-address" name="city_address"
                            placeholder="e.g. Tagaytay City">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-3">
                        <label for="contact-number" class="mb-1">Contact Number</label>
                        <input type="tel" class="form-control" id="contact-number" name="contact_number"
                            placeholder="e.g. 0912 345 6789">
                    </div>
                    <div class="form-group col-4">
                        <label for="email-address" class="mb-1">Email Address</label>
                        <input type="email" class="form-control" id="email-address" name="email_address"
                            placeholder="e.g. mail@gmail.com">
                    </div>
                    <div class="form-group col-3">
                        <label for="birthday" class="mb-1">Date of Birth</label>
                        <input type="text" class="form-control calendar-picker" id="birthday" name="birthday"
                            placeholder="Select Date of Birth" required>
                    </div>
                    <div class="form-group col-2">
                        <p class="mb-1 d-block">Gender</p>
                        <input class="btn-check text-dark " type="radio" name="gender" id="btn-check-male-outlined"
                            value="Male" autocomplete="off">
                        <label class="btn btn-outline-secondary d-inline-block px-4 mb-1 mb-lg-0"
                            for="btn-check-male-outlined">Male</label>
                        <input class="btn-check text-dark" type="radio" name="gender" id="btn-check-female-outlined"
                            value="Female" autocomplete="off">
                        <label class="btn btn-outline-secondary d-inline-block px-3"
                            for="btn-check-female-outlined">Female</label>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="form-group col-3">
                        <label for="marital-status">Marital Status</label>
                        <select class="form-select" id="marital-status" class="" aria-label="Marital Status">
                            <option hidden>Select Marital Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced/Separated/Annulled">Divorced/Separated/Annulled</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label for="joined-date">Date of First Visit</label>
                        <input type="text" class="form-control calendar-picker" id="joined-date" name="joined_date"
                            placeholder="Select Date of First Visit" required>
                    </div>
                    <div class="form-group col-3">
                        <label for="baptism-date">Date of Baptism (if Applicable)</label>
                        <input type="text" class="form-control calendar-picker" id="baptism-date" name="baptism_date"
                            placeholder="Select Date of Baptism" required>
                    </div>
                    <div class="form-group col-3">
                        <div class="form-check my-3">
                            <input class="form-check-input" type="checkbox" value="verified" id="check-information">
                            <label class="form-check-label" for="check-information">
                                I have read the information and reviewed what was entered in the form, I ensure that
                                everything is correct.
                            </label>
                        </div>
                        <div class="d-flex justify-content-end align-items-end">
                            <div class="me-2 d-inline-block"><a href="{{ route('profiles') }}">
                                    <button class="btn btn-outline-danger px-4"><i class="fa-solid fa-x"></i>
                                        Cancel</button>
                                </a>
                            </div>
                            <div class="me-2 d-inline-block"><a href="{{ route('profiles') }}">
                                    <button class="btn btn-success px-4"><i class="fa-solid fa-check"></i> Save
                                        Profile</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
