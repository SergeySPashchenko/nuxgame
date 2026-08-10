@extends('layouts.app')

@section('title', 'Page A — NuxGame')

@section('content')
    <h1>Page A</h1>
    <p>Hello, <strong style="color: var(--text)">{{ $player->username }}</strong></p>

    <div class="panel meta">
        <p><strong>Expires at:</strong> {{ $accessLink->expires_at }}</p>
        <p><strong>Current link:</strong> {{ url()->route('page.show', $accessLink) }}</p>
    </div>

    <div class="actions">
        <form method="post" action="{{ route('page.regenerate', $accessLink) }}">
            @csrf
            <button type="submit" class="secondary">Regenerate link</button>
        </form>

        <form method="post" action="{{ route('page.deactivate', $accessLink) }}">
            @csrf
            <button type="submit" class="danger">Deactivate link</button>
        </form>

        <form method="post" action="{{ route('page.lucky', $accessLink) }}">
            @csrf
            <button type="submit">ImFeelingLucky</button>
        </form>

        <form method="get" action="{{ route('page.history', $accessLink) }}">
            <button type="submit" class="secondary">History</button>
        </form>
    </div>

    @if ($result)
        <h2>Lucky result</h2>
        <div class="panel">
            <p><strong style="color: var(--text)">Number:</strong> {{ $result->number }}</p>
            <p>
                <strong style="color: var(--text)">Result:</strong>
                <span class="{{ $result->is_win ? 'result-win' : 'result-lose' }}">
                    {{ $result->is_win ? 'Win' : 'Lose' }}
                </span>
            </p>
            <p><strong style="color: var(--text)">Amount:</strong> {{ $result->amount }}</p>
        </div>
    @endif

    @if ($history !== null)
        <h2>History (last 3)</h2>
        <div class="panel">
            @forelse ($history as $item)
                <div class="history-item">
                    Number: {{ $item->number }},
                    Result:
                    <span class="{{ $item->is_win ? 'result-win' : 'result-lose' }}">
                        {{ $item->is_win ? 'Win' : 'Lose' }}
                    </span>,
                    Amount: {{ $item->amount }},
                    At: {{ $item->created_at }}
                </div>
            @empty
                <p>No games yet.</p>
            @endforelse
        </div>
    @endif
@endsection
