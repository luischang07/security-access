@extends('layouts.patient-spa')

@section('title', __('patient.dashboard.title'))

@section('spa-content')
    @include('patient.partials.dashboard-content')
@endsection
