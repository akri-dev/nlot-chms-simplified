@extends('layouts.auth')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-2">
        <div class="col d-flex justify-content-start align-items-center">
            <h1>{{ \Carbon\Carbon::now()->format('F Y') }}</h1>
        </div>
        <div class="col d-flex justify-content-end align-items-center">
            <div class="me-2"><a href="{{ route('profiles') }}">
                    <button class="btn btn-success"><i class="fa-solid fa-user"></i> Profile List</button>
                </a>
            </div>
            <div class="me-2"><button class="btn btn-primary"><i class="fa-solid fa-calendar-week"></i> Events List</button>
            </div>
            <div><button class="btn btn-dark"><i class="fa-solid fa-calendar-week"></i> Calendar</button></div>
        </div>
    </div>
    <div class="row">
        <div class="card col-8">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h3 class="h4 mb-1">Birthdays</h3>
                        <ol class="list-group list-group-numbered">
                            {{-- REFACTORED BIRTHDAY LIST --}}
                            @forelse ($birthdays as $profile)
                            <li class="list-group-item d-flex align-items-center">
                                {{-- Output: "Full Name of Celebrant - Current Month Day" --}}
                                <p class="mb-0 ms-1 me-auto">{{ $profile->full_name }} - {{ $profile->birthday_month_day }}</p>

                                {{-- Action Buttons (assuming a route to view profile exists) --}}
                                <a href="{{ route('profiles.profile', $profile->id) }}" class="btn btn-info btn-sm" title="Profile"><i
                                    class="fa-solid fa-user"></i> View Profile</a>
                            </li>
                            @empty
                            <li class="list-group-item d-flex align-items-center">
                                <p class="mb-0 ms-1 me-auto">No birthdays recorded for this month.</p>
                            </li>
                            @endforelse
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <h3 class="h4 mb-1">Anniversaries</h3>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item d-flex align-items-center">
                                {{-- Name and Birthday --}}
                                <p class="mb-0 ms-1 me-auto">Cesar & Lourdes - October 04</p>

                                {{-- Action Buttons --}}
                                <a href="" class="btn btn-light btn-sm" title="Profile"><i
                                        class="fa-solid fa-calendar-days"></i></i> Check Calendar</a>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                {{-- Name and Birthday --}}
                                <p class="mb-0 ms-1 me-auto">John & Geraldine - October 08</p>

                                {{-- Action Buttons --}}
                                <a href="" class="btn btn-light btn-sm" title="Profile"><i
                                        class="fa-solid fa-calendar-days"></i> Check Calendar</a>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <h3 class="h4 mb-1">Events</h3>
                        <ol class="list-group">
                            <li class="list-group-item d-flex align-items-center">
                                {{-- Name and Birthday --}}
                                <p class="mb-0 ms-1 me-auto">John & Geraldine - October 08</p>

                                {{-- Action Buttons --}}
                                <a href="" class="btn btn-light btn-sm" title="Profile"><i
                                        class="fa-solid fa-calendar-days"></i> Check Calendar</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 text-center">
            <h4 class="h6 mb-1">Active Members</h4>
            <div class="display-6 w-75 mx-auto py-1 border border-success text-success mb-1"><i
                    class="fa-solid fa-users"></i> {{ $profile_active }}</div>
            <h4 class="h6 mb-1">Inactive Members</h4>
            <div class="display-6 w-75 mx-auto py-1 border border-danger text-danger mb-1"><i
                    class="fa-solid fa-users-slash"></i> {{ $profile_inactive }}</div>
            <h4 class="h6 mb-1">Volunteers, Staff, & Leaders</h4>
            <div class="display-6 w-75 mx-auto py-1 border border-warning text-warning mb-1"><i
                    class="fa-solid fa-users-between-lines"></i> {{ $profiles_role }}</div>
            <h4 class="h6 mb-1">Recorded Profiles</h4>
            <div class="display-6 w-75 mx-auto py-1 border border-primary text-primary mb-1"><i
                    class="fa-solid fa-users-rectangle"></i> {{ $profile_count }}</div>
        </div>
    </div>

@endsection
