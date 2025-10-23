@extends('layout.app')

@section('content')
    @include('layout.parts.errors')
    <div class="card">
        <div class="card-header">
            <h1 class="text-center">{{ $event->getName() }}</h1>
        </div>
        <div class="card-body">
            @if($event->image)
                <div class="text-center mb-4">
                    <img src="{{ Storage::url($event->image) }}" class="img-fluid rounded" alt="{{ $event->name }}" style="max-height: 400px;">
                </div>
            @endif

            <h4 class="text-center">{{ $event->date }} {{ $event->time }}</h4>
            <p class="text-center">{{ $event->getDescription() }}</p>
            <div class="mt-2">
                <p class="text-center">{{ __('app.stadium') }}: {{ $event->stadium->name }}</p>
                <p class="text-center">{{ __('app.address') }}: {{ $event->stadium->city }} {{ __('app.st') }} {{ $event->stadium->street }}, {{ $event->stadium->numberBuilding }}</p>
            </div>
        </div>
    </div>

    <div class="mt-3">
        @include('event.part.buy-ticket')

        <h4 class="mt-3">{{ __('app.comments') }}</h4>
        <div style="max-height: 35vh" class="overflow-auto mb-5">
            @foreach($event->commentsSort() as $comment)
                <div class="card m-2">
                    <div class="card-body">
                        <h5 class="card-title">
                            @if ($comment->user) 
                                {{ $comment->user->first_name }} {{ $comment->user->last_name }} 
                            @else 
                                {{ __('app.deleted_user') }} 
                            @endif
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $comment->date }} {{ $comment->time }}</h6>
                        <p class="card-text">{{ $comment->getContent() }}</p>
                        <button class="reportButton btn btn-danger" data-bs-toggle="modal" data-bs-target="#registrationCommentModal" value="{{ $comment->id }}">
                            {{ __('app.submission_comment') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        @include('event.part.add-comment')
        @include('event.part.report-comment')
    </div>
    @include('event.part.script')
@endsection
