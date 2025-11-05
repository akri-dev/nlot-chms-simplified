<div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Profiles to Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profiles.anniversaries.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row mb-2">
                        <div>
                            <table class="table align-middle bg-white border text-center">
                                <thead class="small table-success">
                                    <tr>
                                        <th class="px-4">HUSBAND</th>
                                        <th class="px-4">WIFE</th>
                                        <th class="px-4">DATE OF MARRIAGE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4">
                                            <select class="form-select" name="husband_id" aria-label="Select Husband" @if (!$male_unlinked_profiles->isNotEmpty()) disabled @endif>
                                                @if ($male_unlinked_profiles->isNotEmpty())
                                                    <option hidden>Select Husband</option>
                                                    @foreach ($male_unlinked_profiles as $male_unlinked_profile)
                                                        <option value="{{ $male_unlinked_profile->id }}">
                                                            {{ $male_unlinked_profile->full_name }}</option>
                                                    @endforeach
                                                @else
                                                    <option hidden>No Record</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td class="px-4">
                                            <select class="form-select" name="wife_id" aria-label="Select Wife" @if (!$female_unlinked_profiles->isNotEmpty()) disabled @endif>
                                                @if ($female_unlinked_profiles->isNotEmpty())
                                                    <option hidden>Select Wife</option>
                                                    @foreach ($female_unlinked_profiles as $female_unlinked_profile)
                                                        <option value="{{ $female_unlinked_profile->id }}">
                                                            {{ $female_unlinked_profile->full_name }}</option>
                                                    @endforeach
                                                @else
                                                    <option hidden>No Record</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td class="px-4">
                                            <input type="text" class="form-control calendar-picker"
                                                id="marriage_date" name="marriage_date"
                                                placeholder="Select Date of Marriage" @if (!$profiles_needing_link->isNotEmpty()) disabled @endif>
                                            @error('marriage_date')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-2 d-inline-block"><button type="button" class="btn btn-outline-danger px-4" data-bs-dismiss="modal"><i class="fa-solid fa-x"></i>Cancel</button>

                    </div>
                    <div class="me-2 d-inline-block">
                        <button type="submit" class="btn btn-success px-4"><i class="fa-solid fa-check"></i> Save
                            Marriage</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
