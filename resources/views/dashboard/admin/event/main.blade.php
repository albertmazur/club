@extends('dashboard.main')

@section('dashboard.content')
    <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
        @include('event.form-search', ['route' => 'event.dashboard'])   
    </div>
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead>
                @section('headerTable')
                    <tr>
                        <th>{{ __('app.lp') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.date') }}</th>
                        <th>{{ __('app.free_places') }}</th>
                        <th>{{ __('app.price') }}</th>
                        <th>{{ __('app.actions') }}</th>
                    </tr>
                @endsection
            </thead>
            @yield('headerTable')
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $loop->iteration+(($events->currentPage()-1)*10) }}</td>
                        <td>{{ $event->getName() }}</td>
                        <td>{{ $event->date }} {{ $event->time }}</td>
                        <td>{{ $event->freePlaces() }}</td>
                        <td>{{ $event->formatted_price }}</td>
                        <td>
                            <a class="btn btn-info" role="button" href="{{ route('event.edit', ['eventId' => $event->id]) }}">{{ __('app.edit') }}</a>
                            <a class="btn btn-secondary" role="button" href="{{ route('event.show', ['eventId' => $event->id]) }}">{{ __('app.details') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @yield('headerTable')
            </tfoot>
        </table>
    </div>
    <div class="d-flex justify-content-center">{{ $events->links() }}</div>
    <div>
        <h4>{{ __('dashboard.event.add') }}</h4>
        @include('dashboard.admin.event.form', ['event' => null, 'stadiums' => $stadiums])
    </div>
@endsection
