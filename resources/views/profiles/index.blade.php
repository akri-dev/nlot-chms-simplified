@extends('layouts.app')

@section('title', 'Profile List')

@section('content')
    <div class="row mb-2">
        <div class="d-flex justify-content-end"><a href="{{ route('profiles.create') }}"><button class="btn btn-success"><i class="fa-solid fa-plus"></i> Add Profile</button></a></div>
    </div>
    <div class="row mb-2">
        <table class="table table-hover align-middle bg-white border text-center">
            <thead class="small table-success">
                <tr>
                    <th>NAME</th>
                    <th>CONTACT NO.</th>
                    <th>CITY LIVING IN</th>
                    <th>BIRTHDAY</th>
                    <th>MEMBER STATUS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <td>Burio, Alec Joseph S.</td>
                <td>(+63) 939 267 0582</td>
                <td>Tagaytay City</td>
                <td>May 01, 1997</td>
                <td>Active - Leader, Staff</td>
                <td><button class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i></button></td>
            </tbody>
        </table>
    </div>
@endsection
