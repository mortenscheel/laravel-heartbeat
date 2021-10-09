@component('mail::message')
    # {{ $heartbeats->count() }} unhealthy {{ Str::plural('heartbeat', $heartbeats->count()) }}
@component('mail::table')
    | Queue | Last heartbeat | Max allowed |
    | :---- | :------------- | :---------- |
    @foreach($heartbeats as $heartbeat)
        | {{ $heartbeat->getQueue() }} | {{ $heartbeat->diffForHumans() }} | {{ $heartbeat->maxTimeForHumans() }}
    @endforeach
@endcomponent
@endcomponent
