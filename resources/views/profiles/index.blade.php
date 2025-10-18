@extends('layouts.app')

@section('title', 'Profile List')

@section('content')
    <div class="row mb-2">
        <div class="col-6 d-flex justify-content-start align-items-center">
            <h1>Profile list</h1>
        </div>
        <div class="col-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('profiles.anniversaries') }}">
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
                        @php
                            // 1. Check the primary member status first
                            $isInactive = $profile->member_status->value === \App\Enums\MemberStatusEnum::INACTIVE->value;
                            
                            if ($isInactive) {
                                // If Inactive, display "Inactive" and use the danger styling
                                $output = 'Inactive';
                                $statusClass = 'text-danger fw-bold';
                                $iconClass = 'fa-solid fa-ban'; 
                            } else {
                                // 2. If Active, proceed with complex role calculation (your existing logic)
                                $baseRoleSlugs = ['staff', 'volunteer'];
                                $profileRoles = $profile->roles->pluck('slug');
                                
                                // Check if *only* the 'member' role exists
                                $isMemberOnly = $profileRoles->count() === 1 && $profileRoles->contains('member');
                                
                                $baseRole = $profileRoles->intersect($baseRoleSlugs)->first(); // Gets 'staff' or 'volunteer'

                                $specificRoles = $profileRoles->reject(
                                    fn($slug) =>
                                    $slug === 'member' || in_array($slug, $baseRoleSlugs)
                                )->map(fn($slug) => ucwords(str_replace('_', ' ', $slug))) // Format specific roles
                                 ->toArray();

                                $output = '';
                                $iconClass = 'fa-solid fa-check-circle'; // Default icon for Active

                                if ($isMemberOnly) {
                                    // NEW: Member Only Logic
                                    $output = 'Member';
                                    $statusClass = 'text-primary'; // <-- SET TO TEXT-PRIMARY
                                } elseif (empty($baseRole)) {
                                    // Only 'Member' role is present (should be caught by $isMemberOnly, but kept for safety)
                                    $output = 'Member';
                                    $statusClass = 'text-primary'; // Safety fallback
                                } else {
                                    // Base Role (Staff/Volunteer) + Specific Roles
                                    $displayBaseRole = ucwords($baseRole);
                                    $specificRolesDisplay = !empty($specificRoles)
                                        ? ' - ' . implode(', ', $specificRoles)
                                        : '';
                                        
                                    $output = $displayBaseRole . $specificRolesDisplay;
                                    $statusClass = 'text-success'; // Staff/Volunteer/Specific Role
                                }
                            }
                        @endphp
                        
                        <td @class([$statusClass])>
                            <i class="{{ $iconClass }} me-1"></i> {{ $output }}
                        </td>
                        <td><a href="{{ route('profiles.profile', $profile->id) }}"><button class="btn btn-secondary"><i
                                        class="fa-solid fa-magnifying-glass"></i></button></a></td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
