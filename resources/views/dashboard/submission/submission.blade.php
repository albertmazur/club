@extends('dashboard.main')

@section('dashboard.content')
    <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
        <form action="{{ route('submission.index') }}" method="GET">
            <div class="mb-3">
                <label for="content" class="form-label">{{ __('app.find') }}</label>
                <input type="text" id="content" name="content" class="form-control" placeholder="{{ __('app.enter_description') }}" value="{{ $content ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">{{ __('dashboard.choose_topic') }}</label>
                <select name="reason" class="form-select rounded-start" aria-label="{{ __('dashboard.comment.which') }}">
                    <option @if($reason == null) selected @endif value="">{{ __('app.all') }}</option>
                    @foreach(\App\Enums\ReasonSubmission::cases() as $r)
                        <option value="{{ $r->value }}" {{ $reason === $r->value ? 'selected' : '' }}>{{ __('dashboard.comment.' . $r->value) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.find') }}</button>
        </form>
    </div>
    <div style="height: 40vh" class="overflow-auto">
        @foreach($submissions as $submission)
            @include('dashboard.submission.cardSubmission')
        @endforeach
    </div>

    @vite('resources/js/submissionCards.js')
@endsection
