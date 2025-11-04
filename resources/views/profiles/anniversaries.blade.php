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
                    @forelse ($marriages as $marriage)
                        <tr>
                            {{-- HUSBAND COLUMN: Uses the 'husband' relationship and the 'fullName' accessor --}}
                            <td>{{ $marriage->husband->full_name }}</td>

                            {{-- WIFE COLUMN: Uses the 'wife' relationship and the 'fullName' accessor --}}
                            <td>{{ $marriage->wife->full_name }}</td>

                            {{-- DATE OF MARRIAGE COLUMN: Uses the anniversary_date attribute (which is a Carbon instance) --}}
                            <td>{{ $marriage->anniversary_date->format('F d, Y') }}</td>

                            {{-- EDIT BUTTON COLUMN (Matching the front-end structure) --}}
                            <td class="text-right">
                                <a href="" class="btn btn-danger btn-sm">
                                    <i class="fas fa-edit"></i> Edit Anniversary
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No recorded marriages</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
@endsection
