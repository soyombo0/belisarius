<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();


        return view('notes.index', [
            'notes' => $notes
        ]);
    }

    public function show(int $id)
    {
        $note = Note::query()->find($id);
        $tasks = $note->tasks;

        return view('notes.note', [
            'note' => $note,
            'tasks' => $tasks
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'noteName' => ['string'],
            'description' => ['string']
        ]);

        $note = Note::query()->create([
            'name' => $data['noteName']
        ]);

        $note->creator_id = auth()->user()->id;
        $note->save();

        return $note;
    }

    public function update(int $id, Request $request)
    {
        $note = Note::find($id);
        $data = $request->validate([
            'description' => ['string'],
        ]);

        $note->update($data);

        return $note;
    }

    public function destroy(int $noteId)
    {
        $note = Note::find($noteId);
        $note->delete();
    }
}
