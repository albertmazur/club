@use('App\Enums\Language')
<div class="mb-3">
    <label for="languageSwitcher" class="form-label">{{ __('dashboard.selected_language') }}</label>
    <div class="input-group">
        <select id="languageSwitcher" class="form-select">
            @foreach(Language::cases() as $language)
                <option value="{{ $language->value }}" {{ session('language', app()->getLocale()) === $language->value ? 'selected' : '' }}>
                    {{ strtoupper($language->value) }}
                </option>
            @endforeach
        </select>
        <button type="button" id="autoTranslateBtn" class="btn btn btn-info">
            {{ __('dashboard.autotranslate_empty_from_selected') }}
        </button>
    </div>
</div>
<span id="autoTranslateSpinner" class="spinner-border spinner-border-sm text-secondary d-none"role="status" aria-hidden="true"></span>

<form style="margin-top: 50px" method="POST" action="{{ isset($event) ? route('event.update') : route('event.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($event))
        @method('PUT')
        <input type="hidden" name="id" value="{{ $event->id }}" />
    @endif

    <div class="mb-3">
        <label for="stadium_id" class="form-label">{{ __('app.stadium') }}</label>
        <select name="stadium_id" id="stadium_id" class="form-select" aria-label="{{ __('dashboard.stadium.choose') }}" required>
            @foreach($stadiums as $stadium)
                <option value="{{ $stadium->id }}" @if(isset($event) && $stadium->id == $event->stadium_id) selected @endif>{{ $stadium->name }}</option>
            @endforeach
        </select>
    </div>

    @foreach(Language::cases() as $language)
        <div class="mb-3 i18n-field d-none" data-lang="{{ $language->value }}">
            <label class="form-label">
                {{ __('app.name') }} ({{ strtoupper($language->value) }})
            </label>
            <input type="text" class="form-control" name="name[{{ $language->value }}]" value="{{ old("name.{$language->value}", $event->name[$language->value] ?? '') }}" required>
        </div>
    @endforeach

    @foreach(Language::cases() as $language)
        <div class="mb-3 i18n-field d-none" data-lang="{{ $language->value }}">
            <label class="form-label">
                {{ __('app.description') }} ({{ strtoupper($language->value) }})
            </label>
            <textarea class="form-control" name="description[{{ $language->value }}]" rows="4">{{ old("description.{$language->value}", $event->description[$language->value] ?? '') }}</textarea>
        </div>
    @endforeach

    <div class="mb-3">
        <label for="date" class="form-label">{{ __('app.date') }}</label>
        <input type="date" class="form-control" id="date" name="date" value="{{ isset($event->date) ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '' }}">
    </div>

    <div class="mb-3">
        <label for="time" class="form-label">{{ __('app.hour') }}</label>
        <input type="time" class="form-control" id="time" name="time" value="{{ isset($event) ? substr($event->time, 0, 5) : '' }}">
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">{{ __('app.price') }}</label>
        <input type="number" min="0" step="0.01" class="form-control" id="price" name="price" value="{{ $event->price ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">{{ __('app.image') }}</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        @if(isset($event) && $event->image)
            <div class="mt-2">
                <img src="{{ Storage::url($event->image) }}" alt="{{ $event->name }}" style="max-width: 200px;">
                <small class="d-block">{{ __('app.current_image') }}</small>
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary me-auto">
        {{ isset($event) ? __('app.save_changes') : __('app.add') }}
    </button>
</form>

@vite('resources/js/changeLangForm.js')