@extends('layouts.header')
@section('content')

<div class="container">

    <h2>Profile</h2>

    <p><strong data-field="name">Name:</strong> <span id="displayName">{{ $user->name }}</span></p>
    <p><strong data-field="email">Email:</strong> <span id="displayEmail">{{ $user->email }}</span></p> 
    <p><strong data-field="phone">Phone:</strong> <span id="displayPhone">{{ $customer->phone }}</span></p>
    <p><strong data-field="address">Address:</strong> <span id="displayAddress">{{ $customer->addressline }}</span></p>

    <!-- Edit button to trigger the modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
        Edit Profile
    </button>

    <!-- Modal for editing profile -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editProfileForm" action="{{ route('customer.updateProfile', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Hidden input to send PUT request -->
                    <input type="hidden" id="customerId" name="customer_id" value="{{ $customer->customer_id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="addressline" value="{{ $customer->addressline }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="customerUpdate" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection