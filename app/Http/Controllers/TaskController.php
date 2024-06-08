<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function show(int $id)
    {
        $task = Task::find($id);
        $tags = $task->tags;

        return view('tasks.index', [
            'task' => $task,
            'tags' => $tags
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'taskName' => ['string'],
            'description' => ['string'],
            'note_id' => ['required', 'int']
        ]);

        $task = Task::query()->create([
            'name' => $data['taskName']
        ]);

        $note = Note::find($data['note_id']);

        $task->note()->associate($note);
        $task->save();

        $allTags = Tag::all();
        $tasks = $note->tasks;

        return view('notes.note', [
            'note' => $note,
            'tasks' => $tasks,
            'allTags' => $allTags
        ]);
    }

    public function update(Request $request, int $id)
    {
        $task = Task::find($id);
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string'
        ]);

        $task->update($data);

        return $task;
    }

    public function uploadImage(Request $request, int $id)
    {
        $data = $request->validate([
            'taskImage' => 'file'
        ]);

        $task = Task::find($id);
        $path = Storage::disk('public')->put('/', $data['taskImage']);
        $task->image = Storage::url($path);
        $task->save();

        $tags = $task->tags;

        return view('tasks.index', [
            'task' => $task,
            'tags' => $tags
        ]);
    }

    public function deleteImage(Request $request, int $id)
    {
        $task = Task::find($id);

        $task->image = '';
        $task->save();
    }

    public function destroy(Request $request, int $id)
    {
        $task = Task::find($id);
        $taskTags = $task->tags;

        foreach ($taskTags as $tag) {
            $tag->delete();
        }

        $task->delete();
        $note = Note::find($request['note_id']);
        $allTags = Tag::all();
        $tasks = $note->tasks;

        return view('notes.note', [
            'note' => $note,
            'tasks' => $tasks,
            'allTags' => $allTags
        ]);

    }

    public function indexByTags(Request $request)
    {
        $note = Note::query()->find($request['note_id']);
        $tagNames = $request['tagNames'];
        $allTags = Tag::all();
        $taskName = $request['taskNames'];

        if (isset($taskName)) {
            foreach ($tagNames as $tagName) {
                $tasks = Task::query()->whereHas('tags', function ($q) use ($tagName) {
                    $q->where('name', $tagName);
                })->where('name', $taskName)->get();
            }

        } else {
            foreach ($tagNames as $tagName) {
                $tasks = Task::query()->whereHas('tags', function ($q) use ($tagName) {
                    $q->where('name', $tagName);
                })->get();
            }
        }

        return view('notes.note', [
            'note' => $note,
            'tasks' => $tasks,
            'allTags' => $allTags
        ]);
    }

}
