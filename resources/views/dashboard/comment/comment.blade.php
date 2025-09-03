@extends('dashboard.main')

@section('dashboard.content')
    <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
        <form action="{{ route('comment.index') }}" method="GET">
            <div class="mb-3">
                <label for="content" class="form-label">{{ __('app.find') }}</label>
                <input type="text" id="content" name="content" class="form-control" placeholder="{{ __('app.enter_description') }}" value="{{ $content ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="who" class="form-label">{{ __('app.user') }}</label>
                <select id="who" name="who" class="form-select" aria-label="{{ __('dashboard.user.select') }}">
                    <option @if($who) selected @endif value="">{{ __('app.all') }}</option>
                    @foreach($users as $user)
                        <option @if($who == $user->id) selected @endif value="{{ $user->id }}">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="event" class="form-label">{{ __('app.event') }}</label>
                <select id="event" name="event" class="form-select" aria-label="{{ __('dashboard.event.select') }}">
                    <option @if(!$event) selected @endif value="">{{ __('app.all') }}</option>
                    @foreach($events as $e)
                        <option @if($event == $e->id) selected @endif value="{{ $e->id }}">{{ $e->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.find') }}</button>
        </form>
    </div>
    <div style="height: 40vh" class="overflow-auto">
        @foreach($comments as $comment)
            @include('dashboard.comment.cardComment')
        @endforeach
    </div>
@endsection
