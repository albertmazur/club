<div class="card mb-4 shadow-sm">
    <h5 class="card-header">{{ __('app.submission') }}</h5>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">{{ __('dashboard.comment.' . $submission->reason) }}</h6>
                <p class="mb-2">
                    <strong>{{ __('dashboard.submission.content') }}:</strong><br>
                    {{ $submission->content }}
                </p>
            </div>

            <div class="col-md-6">
                @if ($submission->comment)
                    <p class="mb-2">
                        <strong>{{ __('dashboard.comment.event_name') }}:</strong><br>
                        {{ $submission->comment->event->name }}
                    </p>
                    <p class="mb-2">
                        <strong>{{ __('dashboard.comment.content') }}:</strong><br>
                        {{ $submission->comment->content }}
                    </p>
                @else
                    <p class="text-danger">
                        <strong>{{ __('dashboard.submission.not_comment') }}</strong>
                    </p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('submission.remove') }}" class="mt-3">
            @method('delete')
            @csrf
            <div class="input-group">
                <input type="hidden" name="id" value="{{ $submission->id }}">
                <div class="form-floating">
                    <select class="form-select" id="action" name="action" aria-label="{{ __('dashboard.submission.select_action') }}">
                        <option value="only_submission">
                            {{ __('dashboard.submission.delete_only_submission') }}
                        </option>
                        <option value="submission_and_comment">
                            {{ __('dashboard.submission.delete_submission_and_comment') }}
                        </option>
                    </select>
                    <label for="action">{{ __('dashboard.submission.action_label') }}</label>
                </div>
                <button type="submit" class="btn btn-danger px-4">
                    {{ __('dashboard.submission.execute_action') }}
                </button>
            </div>
        </form>
    </div>
</div>
