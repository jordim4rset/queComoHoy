<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventos = Event::get();
        return view('events.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("events.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $eventos = new Event();
        $generatedName = $request->file('photo')->store('img/recipes/cover', 'public');
        $eventos->title = $request->input('title');
        $eventos->description = $request->input('description');
        $eventos->photo = $generatedName;
        $eventos->start_date = $request->input('start_date');
        $eventos->end_date = $request->input('end_date');
        if ($request->input('visibility') == 'on') {
            $eventos->visibility = 1;
        } else {
            $eventos->visibility = 0;
        }
        $eventos->save();
        return redirect()->route('events.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $eventos)
    {
        if (!$eventos->visibility) {
            return redirect()->route('events.index');
        }
        return view('events.show', compact('eventos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $eventos)
    {
        return view('events.edit', compact('eventos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $eventos)
    {

        $generatedName = $request->file('photo')->store('img/recipes/cover', 'public');
        $eventos->title = $request->input('title');
        $eventos->description = $request->input('description');
        $eventos->start_date = $request->input('start_date');
        $eventos->end_date = $request->input('end_date');
        $eventos->photo = $generatedName;

        if ($request->input('visibility') == 'on') {
            $eventos->visibility = 1;
        } else {
            $eventos->visibility = 0;
        }

        $eventos->update();
        return redirect()->route('events.show', $eventos);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $eventos)
    {
        $eventos->delete();
        return redirect()->route('events.index');
    }
}
