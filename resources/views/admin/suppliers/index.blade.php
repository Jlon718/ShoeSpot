@extends('layouts.admin')

@section('content')
<div id="items" class="container">
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#supModal">Add <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
    <div class="card-body" style="height: 210px;">
        <input type="text" id="supSearch" placeholder="--search--">
    </div>
    <div class="table-responsive">
        <table id="suptable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="supbody"></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="supModal" tabindex="-1" role="dialog" aria-labelledby="supModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supModalLabel">Create New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="supform" method="#" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="supplier_name" class="control-label">Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="control-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number">
                    </div>
                    <div class="form-group">
                        <label for="address" class="control-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="supSubmit" type="button" class="btn btn-primary">Save</button>
                <button id="supUpdate" type="button" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/supplier.js') }}"></script>
@endsection