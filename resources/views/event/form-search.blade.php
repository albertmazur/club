<form action="{{ route($route) }}" method="GET">
    <div class="mb-3">
        <label for="value" class="form-label">{{ __('app.find') }}</label>
        <input type="text" id="value" name="value" class="form-control" placeholder="{{ __('app.enter_name') }}" value="{{ $value ?? '' }}">
    </div>
    <div class="row">
        <div class="col-md-9">
            <label for="facility" class="form-label">{{ __('app.stadium') }}</label>
            <select id="facility" name="facility" class="form-select" aria-label="{{ __('dashboard.sort_choice') }}">
                <option value="0" {{ $facility == 0 ? 'selected' : '' }}>{{ __('app.all') }}</option>
                @foreach ($stadiums as $stadium)
                    <option value="{{ $stadium->id }}" {{ $facility == $stadium->id ? 'selected' : '' }}>
                        {{ $stadium->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="filterData" class="form-label">{{ __('app.date_filter') }}</label>
            <select id="filterData" name="filterData" class="form-select">
                <option value="" {{ request('filterData') == '' ? 'selected' : '' }}>
                    {{ __('app.all') }}
                </option>
                <option value="future" {{ request('filterData') == 'future' ? 'selected' : '' }}>
                    {{ __('app.future')}}
                </option>
                <option value="past" {{ request('filterData') == 'past' ? 'selected' : '' }}>
                    {{ __('app.past') }}
                </option>
            </select>
        </div>
    </div>
    <label for="sortSearch" class="form-label">{{ __('app.sort') }}</label>
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text">{{ __('app.sort') }}</span>
            <select id="sortSearch" name="sortSearch" class="form-select w-75" aria-label="{{ __('dashboard.sort_choice') }}">
                <option value="name" {{ $sortSearch == 'name' ? 'selected' : '' }}>
                    {{ __('app.name') }}
                </option>
                <option value="date" {{ $sortSearch == 'date' ? 'selected' : '' }}>
                    {{ __('app.date') }}
                </option>
                <option value="freeSet" {{ $sortSearch == 'freeSet'? 'selected' : '' }}>
                    {{ __('app.free_places') }}
                </option>
            </select>
            <select id="direction" name="direction" class="form-select" aria-label="{{ __('app.sort_direction') }}">
                <option value="asc" {{ $direction == 'asc' ? 'selected' : '' }}>
                    {{ __('app.asc') }}
                </option>
                <option value="desc" {{ $direction == 'desc' ? 'selected' : '' }}>
                    {{ __('app.desc') }}
                </option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('app.find') }}</button>
</form>