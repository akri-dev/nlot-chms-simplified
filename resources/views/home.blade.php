@extends('layouts.auth')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('Dashboard') }}</div>

        <div class="card-body">
            <button class="btn btn-success float-end"><i class="fa-regular fa-plus"></i> Add Profile</button>
            <h2>October 2025</h2>
            <br>
            <div class="row">
                <div class="col-6">Birthdays
                    <ul>
                        <li>Ahlia - October 26</li>
                    </ul>
                </div>
                <div class="col-6">Anniversaries
                    <ul>
                        <li>Cesar & Lourdes - October 04</li>
                        <li>John & Geraldine - October 08</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-6">Events
                    <ul>
                        <li>Lifegroup Training - October 25</li>
                    </ul>
                </div>
            </div>
            Active Members: 30
            <br>
            Inactive Members: 20
            <br>
            Total Number of Recorded Attendees: 50
            <br>
        </div>
    </div>
@endsection
