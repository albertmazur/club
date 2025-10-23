<div class="card m-2 p-0" style="width: 18rem;">
    <a class="nav-link" href="{{ route('event.show', $event->id) }}">
        @if($event->image)
            <img src="{{ Storage::url($event->image) }}" class="card-img-top object-fit-cover" alt="{{ $event->name }}" height="200px">
        @else
            <div class="card-img-top bg-secondary d-flex justify-content-center align-items-center" style="height: 200px;">
                <span class="text-white">{{ $event->getName() }}</span>
            </div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <h2 class="card-title">{{ $event->getName() }}</h2>
        <p class="card-text">
            {{ __('app.date') }}: {{ $event->date }} {{ $event->time }}<br>
            {{ __('app.stadium') }}: {{ $event->stadium->name }}<br>
            <span @if ($event->freePlaces()<=10) class="text-danger" @endif >{{ __('app.free_places') }}: {{ $event->freePlaces() }}</span> 
        </p>
        <div class="d-flex mt-auto">
            <a class="btn btn-primary ms-auto" href="{{ route('event.show', $event->id) }}" role="button">{{ __('app.see_details') }}</a>
        </div>
    </div>
</div>