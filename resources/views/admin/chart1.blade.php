@extends('layouts.master')
@extends('layouts.admin')

@section('content')
<div>
    <canvas id="titleChart"></canvas>
</div>

<script src="{{ asset('/admin/js/chart1.js') }}"></script>
@endsection
