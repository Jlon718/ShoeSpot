
@extends('layouts.admin')

@section('content')
<div id="products" class="container">
    <div id="productAlertContainer"></div>
    <button type="button" id="productAdd" class="btn btn-info btn-lg" data-toggle="modal" data-target="#productModal">
        Add <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </button>

    <!-- Search input field -->
    <div class="card-body" style="height: 210px;">
        <input type="text" id="itemSearch" placeholder="--search--">
    </div>
    
    <form action="{{ url('product/import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="item_upload" class="form-control"/>
        <button type="submit" class="btn btn-primary">Import Excel File</button>
    </form>

    <!-- Table container -->
    <div class="table-responsive">
        <table id="ptable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Brand ID</th>
                    <th>Description</th>
                    <th>Sell Price</th>
                    <th>Cost Price</th>
                    <th>Images</th>
                    <th>Stock</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="pbody"></tbody>
        </table>
        <!-- Placeholder for Infinite Scroll -->
        <div id="scroll-end"></div>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Create New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pform" method="post" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product_name" class="control-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name">
                        <small id="nameError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                    <div class="form-group">
                        <label for="brand_name" class="control-label">Brand Name</label>
                        <select class="form-control" id="brand_name" name="brand_name">
                            <!-- Options will be populated dynamically -->
                        </select>
                        <small id="brandError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description">
                        <small id="descError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                    <div class="form-group">
                        <label for="sell_price" class="control-label">Sell Price</label>
                        <input type="text" class="form-control" id="sell_price" name="sell_price">
                        <small id="sellError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                    <div class="form-group">
                        <label for="cost_price" class="control-label">Cost Price</label>
                        <input type="text" class="form-control" id="cost_price" name="cost_price">
                        <small id="costError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                    <div class="form-group">
                        <label for="cost_price" class="control-label">Stock</label>
                        <input type="text" class="form-control" id="quantity" name="quantity">
                        <small id="stockError" class="form-text text-danger" style="display:none;"></small>
                    </div>
                    <div class="form-group">
                        <label for="supplier_name" class="control-label">Suppliers</label>
                        <div id="supplier_name" class="form-check-group">
                            <!-- Checkboxes will be populated dynamically -->
                        </div>
                        <small id="supplierError" class="form-text text-danger" style="display:none;"></small>
                    </div>                   
                    <div class="form-group">
                        <label for="images">Images:</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple>
                    </div>
                    <small id="imagesError" class="form-text text-danger" style="display:none;"></small>
                    <div id="existingImages" class="form-group">
                        <!-- Existing images will be appended here -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="productSubmit" type="submit" class="btn btn-primary">Save</button>
                <button id="productUpdate" type="submit" class="btn btn-primary" style="display: none;">Update</button>
            </div>
        </div>
    </div>
</div>
@endsection
