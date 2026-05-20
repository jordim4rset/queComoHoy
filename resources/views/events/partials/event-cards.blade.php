@foreach ($eventos as $evento)
    <article class="event-card {{ $evento->active ? 'is-active' : 'is-inactive' }}">
        <a href="{{ route('eventos.show', ['event' => $evento->id]) }}" class="event-card-media">
            @if($evento->images && count($evento->images))
                <img src="{{ asset('storage/' . $evento->images[0]) }}" alt="{{ $evento->display_name }}">
            @else
                <div class="event-card-placeholder">{{ mb_substr($evento->display_name, 0, 1) }}</div>
            @endif
            <span class="event-status">{{ $evento->active ? __('messages.active') : __('messages.inactive') }}</span>
        </a>
        <div class="event-card-body">
            <h3><a href="{{ route('eventos.show', ['event' => $evento->id]) }}">{{ $evento->display_name }}</a></h3>
            <p>{{ Str::limit($evento->description, 120) }}</p>
        </div>
        <div class="event-card-footer">
            <a href="{{ route('eventos.show', ['event' => $evento->id]) }}" class="event-card-link">{{ __('messages.view') }}</a>
            @auth
                @if(Auth::user()->rol === 'admin')
                    <div class="event-card-actions">
                        <a href="{{ route('events.edit', ['event' => $evento->id]) }}" class="btn btn-sm">{{ __('messages.edit') }}</a>
                        <form action="{{ route('events.toggle', ['event' => $evento->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm">{{ $evento->active ? __('messages.deactivate') : __('messages.activate') }}</button>
                        </form>
                        <form action="{{ route('events.destroy', ['event' => $evento->id]) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_event_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </article>
@endforeach
