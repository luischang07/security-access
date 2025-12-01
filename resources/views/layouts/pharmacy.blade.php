@extends('layouts.app', ['useLandmarkPattern' => true])

@section('content')
  @include('components.topbar', ['user' => auth()->user(), 'type' => 'pharmacy'])

  <div class="flex flex-1 overflow-hidden">
    @include('components.sidebar', [
      'user' => auth()->user(),
      'type' => 'pharmacy',
      'currentRoute' => Route::currentRouteName(),
    ])
          <main class="flex-1 bg-background-light dark:bg-background-dark @yield('pharmacy-main-class', 'overflow-y-auto')">
              @yield('pharmacy-content')
          </main>
      </div>
@endsection
