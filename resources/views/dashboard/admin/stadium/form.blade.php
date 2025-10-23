@use('App\Enums\Language')
<form action="{{ isset($stadium) ? route('stadium.update') : route('stadium.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($stadium))
        @method('PUT')
        <input type="hidden" name="id" value="{{ $stadium->id }}" />
    @endif

    <div class="mb-3">
        <label for="languageSwitcher" class="form-label">{{ __('dashboard.selected_language') }}</label>
        <select id="languageSwitcher" class="form-select">
            @foreach(Language::cases() as $language)
                <option value="{{ $language->value }}" {{ session('language', app()->getLocale()) === $language->value ? 'selected' : '' }}>
                    {{ strtoupper($language->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('dashboard.stadium.name') }}</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $stadium->name ?? '' }}">
    </div>

    @foreach(Language::cases() as $language)
        <div class="mb-3 i18n-field d-none" data-lang="{{ $language->value }}">
            <label class="form-label">
                {{ __('app.description') }} ({{ strtoupper($language->value) }})
            </label>
            <textarea class="form-control" name="description[{{ $language->value }}]" rows="4">{{ old("description.{$language->value}", $stadium->description[$language->value] ?? '') }}</textarea>
        </div>
    @endforeach

    <div class="mb-3">
        <label for="city" class="form-label">{{ __('app.city') }}</label>
        <input type="text" class="form-control" id="city" name="city" value="{{ $stadium->city ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="street" class="form-label">{{ __('app.street') }}</label>
        <input type="text" class="form-control" id="street" name="street" value="{{ $stadium->street ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="numberBuilding" class="form-label">{{ __('app.number_building') }}</label>
        <input type="text" class="form-control" id="numberBuilding" name="numberBuilding" value="{{ $stadium->numberBuilding ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="places" class="form-label">{{ __('app.capacity') }}</label>
        <input type="number" min="0" step="1" class="form-control" id="places" name="places" value="{{ $stadium->places ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">{{ __('app.image') }}</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        @if(isset($stadium) && $stadium->image)
            <div class="mt-2">
                <img src="{{ Storage::url($stadium->image) }}" alt="{{ $stadium->name }}" style="max-width: 200px;">
                <small class="d-block">{{ __('app.current_image') }}</small>
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary me-auto">
        {{ isset($stadium) ? __('app.save_changes') : __('app.add') }}
    </button>
</form>

@vite('resources/js/changeLangForm.js')