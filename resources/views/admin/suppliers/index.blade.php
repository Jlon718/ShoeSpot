@extends('layouts.admin')

@section('content')
<div id="suppliers" class="container">
    <div id="supplierAlertContainer"></div>
    <button type="button" id="supAdd" class="btn btn-info btn-lg" data-toggle="modal" data-target="#supModal">Add supplier<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
    <form id="supplierForm" action="{{ url('supplier/import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="item_upload" class="form-control"/>
        <button type="submit" class="btn btn-primary">Import Excel File</button>
    </form>
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
        <div id="scroll-end"></div>
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
                <form id="supform" method="#" action="#" nctype="multipart/form-data">
                    <div class="form-group">
                        <label for="supplier_name" class="control-label">Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name">
                        <small id="nameError" class="form-text text-danger" style="display:none;">Please enter a supplier name (at least 3 characters long).</small>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email">
                        <small id="emailError" class="form-text text-danger" style="display:none;">Please enter a valid email address.</small>
                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="control-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number">
                        <small id="phoneError" class="form-text text-danger" style="display:none;">Please enter a phone number (09---------).</small>
                    </div>
                    <div class="form-group">
                        <label for="address" class="control-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address">
                        <small id="addressError" class="form-text text-danger" style="display:none;">Please enter an address.</small>
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