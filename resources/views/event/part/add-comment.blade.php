<div class="mt-3">
    @guest
        <h4>{{ __('app.no_account_comment') }}</h4>
        <a href="{{ route('login') }}" class="btn btn-outline-primary">{{ __( 'app.login_in') }}</a>
        <a href="{{ route('register') }}" class="btn btn-outline-primary">{{ __('app.sign_up') }}</a>
    @else
        <h4>{{ __('app.comment_add') }}</h4>
        <form method="POST" action="{{ route('comment.store') }}">
            @csrf
            <div class="form-floating mb-3">
                <textarea class="form-control" id="floatingTextarea" name="content" placeholder=" " style="height: 100px"></textarea>
                <label for="floatingTextarea">{{ __('app.comment') }}</label>
            </div>
            <input type="hidden" name="event_id" value="{{ $event->id}}">
            <input class="btn btn-primary" type="submit" value="{{ __('app.add') }}">
        </form>
    @endguest
</div>