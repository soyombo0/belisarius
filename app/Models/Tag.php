<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
