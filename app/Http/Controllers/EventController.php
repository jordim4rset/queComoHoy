<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event; 
class EventController extends Controller
{
    private const EVENTS_PER_PAGE = 5;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;
        if (request()->user()) {
            $user = request()->user();
        }

        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = request()->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }
        return view("events.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
            'active' => 'nullable',
        ]);

        $eventos = new Event();
        $eventos->name = $validated['name'] ?: $validated['title'];
        $eventos->title = $validated['title'] ?? null;
        $eventos->description = $validated['description'] ?? null;
        $eventos->visibility = 1;
        $eventos->active = $request->has('active') ? 1 : 0;

        // handle multiple images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $images[] = $file->store('img/events', 'public');
                }
            }
        }

        if (!empty($images)) {
            $eventos->images = $images;
        }

        $eventos->save();

        return redirect()->route('events.index')->with('success', 'Evento creado.');
    }

    /**
     * Display public list of active events.
     */
    public function publicIndex(Request $request)
    {
        $user = request()->user();

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

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // allow admins to view inactive events; public users get 404
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $user = request()->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $user = $request->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
            'active' => 'nullable',
        ]);

        $event->name = $validated['name'] ?: $validated['title'];
        $event->title = $validated['title'] ?? null;
        $event->description = $validated['description'] ?? null;
        $event->visibility = $event->visibility ?? 1;
        $event->active = $request->has('active') ? 1 : 0;

        $images = $event->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $images[] = $file->store('img/events', 'public');
                }
            }
        }

        $event->images = $images;

        $event->update();

        return redirect()->route('events.index')->with('success', 'Evento actualizado.');
    }

    /**
     * Toggle active status for admin
     */
    public function toggle(Event $event)
    {
        $user = request()->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

        $event->active = !$event->active;
        $event->save();
        return back()->with('success', 'Estado cambiado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $user = request()->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

        $event->delete();
        return redirect()->route('events.index');
    }
}
