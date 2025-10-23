@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">{{ __('app.club_facilities') }}</h1>

        @if(!empty($stadiums) && count($stadiums) > 0)
            @foreach($stadiums as $stadium)
                <div class="row mb-5 align-items-center">
                    <div class="col-md-6 {{ $loop->iteration % 2 === 0 ? 'order-md-2' : '' }}">
                        <div class="overflow-hidden rounded shadow-sm mb-3 mb-md-0" style="height: 300px;">
                            <img src="{{ Storage::url($stadium->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ __('app.image') . ' ' . $stadium->name }}">
                        </div>
                    </div>
                    <div class="col-md-6 {{ $loop->iteration % 2 === 0 ? 'order-md-1' : '' }}">
                        <h3 class="mt-3 mt-md-0">{{ $stadium->name }}</h3>
                        <p>{{ $stadium->getDescription() }}</p>

                        <ul class="list-unstyled">
                            <li><strong>{{ __('app.city') }}</strong>: {{ $stadium->city }}</li>
                            <li><strong>{{ __('app.address') }}</strong>: {{ $stadium->street }} {{ $stadium->numberBuilding }}</li>
                            <li><strong>{{ __('app.capacity') }}</strong>: {{ $stadium->places }} {{ __('app.places') }}</li>
                        </ul>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                {{ __('app.not_facilities') }}
            </div>
        @endif
    </div>
@endsection