@extends('layouts.admin')

@section('content')
<div id="transactions" class="container">
    <div class="table-responsive">
        <table id="transactionTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Date Placed</th>
                    <th>Date Shipped</th>
                    <th>Shipping Fee</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
        <div id="scroll-end"></div>
    </div>
</div>

<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransactionBtn">Edit Transaction Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="transactionForm">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                        {{-- <option value="3">Cancelled</option>
                            <option value="2">Delivered</option>
                            <option value="1">Shipped</option>
                            <option value="0">Pending</option> --}}
                        </select>
                        <span id="statusError" class="text-danger"></span>
                    </div>
                    <button type="button" id="transactionUpdate" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
