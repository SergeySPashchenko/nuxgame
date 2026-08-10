@extends('layouts.app')

@section('title', 'Link unavailable — NuxGame')

@section('content')
    <p class="status-code">410</p>
    <h1>Access link is no longer valid</h1>
    <div class="panel">
        <p>
            This link has expired or was deactivated (or replaced after regenerate).
            Access to Page A is only available through an active link within its 7-day window.
        </p>
    </div>
    <p><a class="btn" href="{{ route('register') }}">Register again</a></p>
@endsection
