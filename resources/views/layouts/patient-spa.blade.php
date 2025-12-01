@extends('layouts.app')

@section('content')
    @php
        $useLandmarkPattern = true;
        $user = Auth::user();
    @endphp

    @include('components.topbar', ['type' => 'patient', 'user' => $user])

    <div class="flex flex-1 overflow-hidden">
        @include('components.sidebar', [
            'user' => $user,
            'type' => 'patient',
            'currentRoute' => Route::currentRouteName(),
            'useSpa' => true,
        ])

        <!-- Main Content Container -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8 bg-background-light dark:bg-background-dark" id="main-content">
            @include('components.spa-loader')

            <!-- Dynamic Content -->
            @yield('spa-content')
        </main>
    </div>
@endsection
