@extends('layouts.admin')

@section('title', __('admin.profile.title'))

@section('content')
  <div id="main-content">
    @include('admin.partials.profile-content')
  </div>
@endsection