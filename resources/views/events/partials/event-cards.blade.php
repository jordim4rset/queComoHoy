@foreach ($eventos as $evento)
    <article class="event-card" style="border:1px solid #e6e6e6;border-radius:8px;overflow:hidden;display:flex;flex-direction:column">
        <div class="card-media" style="height:140px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;overflow:hidden">
            @if($evento->images && count($evento->images))
                <img src="{{ asset('storage/' . $evento->images[0]) }}" alt="{{ $evento->display_name }}" style="display:block;width:100%;height:140px;object-fit:cover">
            @else
                <div style="padding:12px;color:#999">Sin imagen</div>
            @endif
        </div>
        <div class="card-body" style="padding:12px;flex:1">
            <h3 style="margin:0 0 8px"><a href="{{ route('eventos.show', ['event' => $evento->id]) }}">{{ $evento->display_name }}</a></h3>
            <p style="margin:0 0 12px;color:#555">{{ Str::limit($evento->description, 120) }}</p>
        </div>
        <div class="card-footer" style="padding:10px;border-top:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center">
            <div style="font-size:13px;color:#333">{{ $evento->active ? 'Activo' : 'Inactivo' }}</div>
            @auth
                @if(Auth::user()->rol === 'admin')
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('events.edit', ['event' => $evento->id]) }}" class="btn">Editar</a>
                        <form action="{{ route('events.toggle', ['event' => $evento->id]) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn">{{ $evento->active ? 'Desactivar' : 'Activar' }}</button>
                        </form>
                        <form action="{{ route('events.destroy', ['event' => $evento->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Eliminar evento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </article>
@endforeach
