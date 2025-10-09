@extends('layouts.app')

@section('title', 'Add Profile')

@section('content')
    <form action="">
        <div class="row mb-2">
            <div class="form-group col-4 ">
                <label for="first-name" class="mb-1">First Name</label>
                <input type="text" class="form-control" id="first-name" name="first_name" placeholder="e.g. John" required>
            </div>
            <div class="form-group col-4">
                <label for="middle-name" class="mb-1">Middle Name</label>
                <input type="text" class="form-control" id="middle-name" name="middle_name" placeholder="e.g. Jonah">
            </div>
            <div class="form-group col-4">
                <label for="last-name" class="mb-1">Last Name</label>
                <input type="text" class="form-control" id="last-name" name="last_name" placeholder="e.g. Smith"
                    required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-8">
                <label for="street-address" class="mb-1">Street Address</label>
                <input type="text" class="form-control" id="street-address" name="street_address" placeholder="e.g. 123 Gonzales St.">
            </div>
            <div class="form-group col-4">
                <label for="city-address" class="mb-1">City</label>
                <input type="text" class="form-control" id="city-address" name="city_address" placeholder="e.g. Tagaytay City">
            </div>
        </div>
    </form>
@endsection
