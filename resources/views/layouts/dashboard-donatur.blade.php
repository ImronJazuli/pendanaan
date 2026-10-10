@extends('layouts.app')

@section('content')
    @yield('donatur-content')
    {{ $slot ?? '' }}
@endsection
