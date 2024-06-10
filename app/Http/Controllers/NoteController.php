<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->notes;
        $users = User::all();

        return view('notes.index', [
            'notes' => $notes,
            'users' => $users
        ]);
    }

    public function show(int $id)
    {
        $note = Note::query()->find($id);
        $tasks = $note->tasks;
        $allTags = Tag::all();
        $users = User::all();

        return view('notes.note', [
            'note' => $note,
            'tasks' => $tasks,
            'allTags' => $allTags,
            'users' => $users
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
        $note->users()->attach(auth()->user());
        $note->save();

        $notes = auth()->user()->notes;
//        $users = User::all();

        return view('notes.index', [
            'notes' => $notes,
//            'users' => $users
        ]);
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
        $notes = auth()->user()->notes;
        $users = User::all();

        return view('notes.index', [
            'notes' => $notes,
            'users' => $users
        ]);
    }

    public function shareNote(Request $request)
    {
        $note = Note::find($request['note_id']);
        $user = User::find($request['user_id']);
        $request['userCanEdit'];

        $note->users()->attach(auth()->user());
        $note->save();

        $notes = auth()->user()->notes;
        $users = User::all();

        return view('notes.index', [
            'notes' => $notes,
            'users' => $users
        ]);
    }
}
