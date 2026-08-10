@extends('layouts.app')

@section('title', 'Register — NuxGame')

@section('content')
    <h1>Register</h1>
    <p>Create an account to get a unique Page A link valid for 7 days.</p>

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="panel">
        <form method="post" action="{{ route('register.store') }}">
            @csrf
            <div>
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username">
            </div>
            <div>
                <label for="phonenumber">Phonenumber</label>
                <input id="phonenumber" type="text" name="phonenumber" value="{{ old('phonenumber') }}" required autocomplete="tel">
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
@endsection
