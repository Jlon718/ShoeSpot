@include('layouts.header')

@section('content')
    <div class="container mt-5">
        {!! Form::open(['route' => 'orders.store', 'class' => 'form-control', 'method' => 'post']) !!}
        {!! Form::hidden('date_placed', \Carbon\Carbon::now(), ['style' => 'display: none;']) !!}
        {!! Form::hidden('customer_id', $customer->customer_id) !!}
        {!! Form::hidden('product_id', $product->product_id) !!}
        
        <div class="form-group row">
            {{ Form::label('product_name', 'Product Name', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {!! Form::text('product_name', $product->product_name, ['readonly' => 'readonly', 'class' => 'form-control form-control-lg']) !!}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('sell_price', 'Price', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {!! Form::text('sell_price', $product->sell_price, ['readonly' => 'readonly', 'class' => 'form-control form-control-lg']) !!}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('quantity', 'Quantity', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {!! Form::text('quantity', null, ['class' => 'form-control form-control-lg']) !!}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('customer_name', 'Customer Name', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {!! Form::text('customer_name', $customer->customer_name, ['readonly' => 'readonly', 'class' => 'form-control form-control-lg']) !!}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('phone_number', 'Phone Number', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {!! Form::text('phone_number', $customer->phone_number, ['class' => 'form-control form-control-lg']) !!}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('address', 'Address', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {!! Form::text('address', $customer->address, ['readonly' => 'readonly', 'class' => 'form-control form-control-lg']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10 offset-sm-2">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-lg']) !!}
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection