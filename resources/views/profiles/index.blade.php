@extends('layouts.app')

@section('title', 'Profile List')

@section('content')
    <div class="row mb-2">
        <div class="col-6 d-flex justify-content-start align-items-center">
            <h1>Profile list</h1>
        </div>
        <div class="col-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('profiles.anniversaries')}}">
                <button class="btn btn-warning me-2">
                    <i class="bi bi-people"></i> Anniversary List</button>
            </a>
            <a href="{{ route('profiles.create') }}">
                <button class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Add Profile</button>
            </a>
        </div>
    </div>
    <div class="row mb-2">
        <table class="table table-hover align-middle bg-white border text-start">
            <thead class="small table-success">
                <tr>
                    <th class="ps-4">NAME</th>
                    <th>CONTACT NO.</th>
                    <th>CITY LIVING IN</th>
                    <th>BIRTHDAY</th>
                    <th>MEMBER STATUS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($all_profiles as $profile)
                    <tr>
                        <td @class(['ps-4'])>{{ $profile->last_name }}, {{ $profile->first_name }}
                            {{ !empty($profile->middle_name_initial) ? $profile->middle_name_initial . '.' : '' }}</td>
                        <td @class([
                            'text-primary' => !empty($profile->contact_number_spaced),
                            'text-danger' => empty($profile->contact_number_spaced),
                        ])>
                            {{ !empty($profile->contact_number_spaced) ? $profile->contact_number_spaced : 'Not specified' }}
                        </td>
                        <td @class([
                            'text-primary' => !empty($profile->city_address),
                            'text-danger' => empty($profile->city_address),
                        ])>
                            {{ !empty($profile->city_address) ? $profile->city_address : 'Not specified' }}
                        </td>
                        <td @class([
                            'text-primary' => !empty($profile->birthday),
                            'text-danger' => empty($profile->birthday),
                        ])>
                            {{ $profile->birthday?->format('F j, Y') ?? 'Not specified' }}
                        </td>
                        <td><i class="bi bi-check-circle-fill text-success"></i> Active - Pastor, Leader, Staff</td>
                        <td><a href="{{ route('profiles.profile', $profile->id) }}"><button class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i></button></a></td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
