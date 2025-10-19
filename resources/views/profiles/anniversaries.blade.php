@extends('layouts.app')

@section('title', 'Anniversaries')

@section('content')
    @include('profiles.anniversary-modal.link')
    <div class="row mb-2">
        <div class="col-6 d-flex justify-content-start align-items-center">
            <h1>Anniversaries</h1>
        </div>
        <div class="col-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('profiles') }}">
                <button class="btn btn-success me-2"><i class="fa-solid fa-user"></i> Profile List</button>
            </a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#linkModal" class="btn btn-outline-warning">
                <i class="fa-solid fa-link"></i> Profiles to Link</button>
        </div>
    </div>
    <div class="row mb-2">
        <div>
            <table class="table table-hover align-middle bg-white border text-center">
                <thead class="small table-success">
                    <tr>
                        <th class="ps-4" style="width: 25%">HUSBAND</th>
                        <th style="width: 25%">WIFE</th>
                        <th>DATE OF MARRIAGE</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Araneta, Joseph</td>
                        <td>Araneta, Carmen</td>
                        <td>December 21, 1960</td>
                        <td>
                            <a href="">
                                <button class="btn btn-danger">
                                    <i class="fa-solid fa-pencil"></i> Edit Anniversary</button>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
