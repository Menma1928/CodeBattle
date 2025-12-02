<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index(){
        $events = Event::query()->paginate(10);
        return view('eventos.index', compact('events'));
    }

    public function create(){
        $events = Event::all();
        return view('eventos.create', compact('events'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);
        Event::create([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('eventos.index');
    }

    public function edit(Event $evento){
        return view('eventos.edit', compact('evento'));
    }

    public function update(Request $request, Event $evento){
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);
        $evento->update([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
        ]);
        return redirect()->route('eventos.index');
    }

    public function destroy(Event $evento){
        $evento->delete();
        return redirect()->route('eventos.index');
    }

    public function show(Event $evento){
        return view('eventos.evento', compact('evento'));
    }

}
