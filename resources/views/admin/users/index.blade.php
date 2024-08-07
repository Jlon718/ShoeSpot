
@extends('layouts.admin')

@section('content')
<div id="items" class="container">
    <div class="table-responsive">
        <table id="utable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Addressline</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ubody"></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uform" method="#" action="#">
                    <div class="form-group">
                        <label for="role_as">Role</label>
                        <select id="role_as" name="role_as" class="form-control">
                            {{-- <option value="1">Admin</option>
                            <option value="0">User</option> --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            {{-- <option value="processing">processing</option>
                            <option value="delivered">delivered</option> --}}
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="userSubmit" type="submit" class="btn btn-primary">Save</button>
                <button id="userUpdate" type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
@endsection