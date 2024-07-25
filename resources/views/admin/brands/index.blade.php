
@extends('layouts.admin')

@section('content')
<div id="items" class="container">
    <div id="alertContainer"></div>
    <button type="button" id="brandAdd" class="btn btn-info btn-lg" data-toggle="modal" data-target="#itemModal">Add brand<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

    <form id="brandForm" action="{{ url('brand/import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
        @csrf
        <input type="file" name="item_upload" class="form-control"/>
        <button type="submit" class="btn btn-primary">Import Excel File</button>
    </form>
    <div class="table-responsive">
        <table id="itable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ibody"></tbody>
        </table>
        <div id="scroll-end"></div>
    </div>
</div>

<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Create New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="iform" method="#" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <small id="nameError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                    <div class="form-group">
                        <label for="image" class="control-label">Image</label>
                        <input type="file" class="form-control" id="image" name="images">
                        <small id="imageError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="brandClose" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="itemSubmit" type="button" class="btn btn-primary">Save</button>
                <button id="itemUpdate" type="button" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
@endsection