<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;

class EventController extends Controller
{
    private const EVENTS_PER_PAGE = 5;

    public function index(Request $request)
    {
        $this->onlyAdmin($request);

        $eventos = Event::orderBy('id', 'desc')
            ->paginate(self::EVENTS_PER_PAGE);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('events.partials.event-cards', compact('eventos'))->render(),
                'next_page_url' => $eventos->nextPageUrl(),
            ]);
        }

        return view('events.index', compact('eventos'));
    }

    public function create()
    {
        $this->onlyAdmin(request());

        return view("events.create");
    }

    public function store(EventStoreRequest $request)
    {
        $this->onlyAdmin($request);

        $eventos = new Event();
        $this->fillEvent($eventos, $request, 1);

        $eventos->save();

        return redirect()->route('events.index')->with('success', 'Evento creado.');
    }

    public function publicIndex(Request $request)
    {
        $user = $request->user();

        if ($user && $user->rol === 'admin') {
            $eventos = Event::orderBy('id', 'desc')
                ->paginate(self::EVENTS_PER_PAGE);
        } else {
            $eventos = Event::where('active', true)
                ->orderBy('id', 'desc')
                ->paginate(self::EVENTS_PER_PAGE);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('events.partials.event-cards', compact('eventos'))->render(),
                'next_page_url' => $eventos->nextPageUrl(),
            ]);
        }

        return view('events.index', compact('eventos'));
    }

    public function show(Event $event)
    {
        if (!$event->active && (!request()->user() || request()->user()->rol !== 'admin')) {
            abort(404);
        }

        $event->load([
            'recipes' => function ($query) {
                $query
                    ->with(['user', 'likes', 'comments.user'])
                    ->withCount('comments')
                    ->orderBy('recipes.id', 'desc');
            },
        ]);

        $followingUserIds = request()->user()
            ? request()->user()->following()->pluck('users.id')->all()
            : [];

        return view('events.show', compact('event', 'followingUserIds'));
    }

    public function edit(Event $event)
    {
        $this->onlyAdmin(request());

        return view('events.edit', compact('event'));
    }

    public function update(EventUpdateRequest $request, Event $event)
    {
        $this->onlyAdmin($request);

        $this->fillEvent($event, $request, $event->visibility ?? 1);
        $event->save();

        return redirect()->route('events.index')->with('success', 'Evento actualizado.');
    }

    public function toggle(Event $event)
    {
        $this->onlyAdmin(request());

        $event->active = !$event->active;
        $event->save();

        return back()->with('success', 'Estado cambiado.');
    }

    public function destroy(Event $event)
    {
        $this->onlyAdmin(request());

        $event->delete();

        return redirect()->route('events.index');
    }

    private function onlyAdmin(Request $request): void
    {
        if ($request->user()?->rol !== 'admin') {
            abort(403);
        }
    }

    private function fillEvent(Event $event, Request $request, int $visibility): void
    {
        $validated = $request->validated();

        $event->name = $validated['title'];
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->visibility = $visibility;
        $event->active = $request->has('active') ? 1 : 0;

        $images = array_merge($event->images ?? [], $this->uploadedImages($request));

        if ($images) {
            $event->images = $images;
        }
    }

    private function uploadedImages(Request $request): array
    {
        $images = [];

        foreach ($request->file('images', []) as $file) {
            if ($file && $file->isValid()) {
                $images[] = $file->store('img/events', 'public');
            }
        }

        return $images;
    }
}
