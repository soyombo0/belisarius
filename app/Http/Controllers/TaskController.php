<?php

namespace App\Http\Controllers;

use App\Models\Note;
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

        return $task;
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

        return $task;
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
        $task->delete();
    }


}
