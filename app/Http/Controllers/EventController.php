<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;

class EventController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = null;
        if (request()->user()) {
            $user = request()->user();
        }

        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

        $eventos = Event::orderBy('created_at', 'desc')->get();
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
    public function store(EventStoreRequest $request)
    {
        $user = $request->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

        $validated = $request->validated();

        $eventos = new Event();
        $eventos->name = $validated['title'];
        $eventos->title = $validated['title'];
        $eventos->description = $validated['description'];
        $eventos->visibility = 1;
        $eventos->active = $request->has('active') ? 1 : 0;

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
    public function publicIndex()
    {
        $user = request()->user();

        if ($user && $user->rol === 'admin') {
            $eventos = Event::orderBy('created_at', 'desc')->get();
        } else {
            $eventos = Event::where('active', true)
                ->orderBy('created_at', 'desc')
                ->get();
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
        // load related recipes
        $event->load('recipes');
        return view('events.show', compact('event'));
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
    public function update(EventUpdateRequest $request, Event $event)
    {
        $user = $request->user();
        if (!$user || $user->rol !== 'admin') {
            abort(403);
        }

        $validated = $request->validated();

        $event->name = $validated['title'];
        $event->title = $validated['title'];
        $event->description = $validated['description'];
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
