<form action="{{ isset($user) ? route('user.update') : route('user.store') }}" method="POST">
    @csrf
    @if(isset($user))
        @method('PUT')
        <input type="hidden" name="id" value="{{ $user->id }}" />
    @endif

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ __('app.name_user') }}</span>
        </div>
        <input type="text" class="form-control" name="first_name" value="{{ $user->first_name ?? '' }}">
        <input type="text" class="form-control" name="last_name" value="{{ $user->last_name ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('app.e_mail') }}</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="change_password" class="form-label">{{ __('app.password') }}</label>
        <div class="input-group">
            <div class="input-group-text">
                <input type="checkbox" id="change_password" value="1" name="change_password" class="form-check-input">
            </div>
            <label class="input-group-text">{{ __('dashboard.user.new_password') }}</label>
            <input type="password" class="form-control" id="password" name="password" min="8">
        </div>
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">{{ __('app.register.confirm_password') }}</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" min="8">
    </div>
    

    <div class="mb-3">
        <label for="tel" class="form-label">{{ __('app.tel') }}</label>
        <input type="tel" class="form-control" id="tel" name="tel" value="{{ $user->tel ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">{{ __('app.role') }}</label>
        <select id="role" name="role" class="form-select">
            @foreach(\App\Enums\UserRole::cases() as $role)
                <option value="{{ $role->value }}" {{ ($user->role ?? '') === $role->value ? 'selected' : '' }}>
                    {{ __('app.' . $role->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="language" class="form-label">{{ __('app.lang') }}</label>
        <select id="language" name="language" class="form-select">
            @foreach(\App\Enums\Language::cases() as $lang)
                <option value="{{ $lang->value }}" {{ ($user->language ?? '') === $lang->value ? 'selected' : '' }}>
                    {{ __('app.langs.' . $lang->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary me-auto">
        {{ isset($user) ? __('app.save_changes') : __('app.add') }}
    </button>
</form>
