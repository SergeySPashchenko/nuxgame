@extends('layouts.app')

@section('title', 'Link deactivated — NuxGame')

@section('content')
    <p class="status-code">OK</p>
    <h1>Link deactivated</h1>
    <div class="panel">
        <p>This access link has been deactivated and is no longer valid.</p>
        <p>You can register again to receive a new unique link.</p>
    </div>
    <p><a class="btn" href="{{ route('register') }}">Register again</a></p>
@endsection
