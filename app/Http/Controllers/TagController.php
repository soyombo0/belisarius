<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Tag;
class TagController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'tagName' => ['string', 'required'],
            'task_id' => 'int'
        ]);
        $task = Task::find($data['task_id']);

//        dd($data);
        $tag = Tag::query()->create([
            'name' => $data['tagName']
        ]);

        $tag->tasks()->attach($task);
        $tag->save();

        return $tag;
    }

    public function destroy(int $id)
    {
        $tag = Tag::find($id);
        $tag->delete();
    }
}
