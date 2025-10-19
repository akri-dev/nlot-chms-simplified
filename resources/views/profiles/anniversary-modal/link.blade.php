<div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Profiles to Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
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
                                            <select class="form-select" aria-label="Husband Select">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </td>
                                        <td class="px-4">
                                            <select class="form-select" aria-label="Husband Select">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </td>
                                        <td class="px-4">
                                            <input type="text" class="form-control calendar-picker" id="marriage_date"
                                                name="marriage_date" placeholder="Select Date of Marriage">
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
                    <div class="me-2 d-inline-block"><a href="{{ route('profiles') }}">
                            <button type="button" class="btn btn-outline-danger px-4"><i class="fa-solid fa-x"></i>
                                Cancel</button>
                        </a>
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
