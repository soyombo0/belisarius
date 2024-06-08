@extends('navbar')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<div class="d-flex justify-content-center">
    <h3>{{ $note->name }}</h3>

    <div class="">
        <input class="form-control rounded-3 my-1" type="text" name="noteSearch" placeholder="Enter note's name">
        <input class="form-control rounded-3 my-1" type="text" name="tagSearch" placeholder="Enter tag's name">
        <button class="btn btn-primary my-1">Find Task</button>
        <h3>Tasks:</h3>
        @foreach($tasks as $task)
            <div class="bg-light rounded-3 py-2 my-2 text-center">
                <img src="{{ $task->image }}" width="150" height="150">
                <p class=" ">{{ $task->name }}</p>
                <p class="">{{ $task->note_id }}</p>
                <div class="row  text-center">
                    @foreach($task->tags as $tag)
                        <p class="col-5 bg-info rounded-3 mx-1 my-2">{{ $tag->name }}</p>
                    @endforeach
                </div>
                <a href="/task/{{ $task->id }}" class="edit-task  btn btn-primary mx-3">Edit Task</a>
                <button class="delete-task  btn btn-danger mx-3">Delete Task</button>
            </div>
        @endforeach
    </div>

    <div class=" ">
        <form id="storeTask">
            <input class="col-6 form-control  rounded-3 my-1" type="text" name="taskName" placeholder="enter task's name">
            <button class="col-6 delete-note col-3 btn btn-success my-1" type="submit">Add Task</button>
        </form>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#storeTask').on('submit', function(e) {
            e.preventDefault();
            $('.note-name-input').text('');
            $.ajax({
                url: '{{ route('tasks') }}',
                method: 'POST',
                data: {
                    'taskName': $(this).find('input[name="taskName"]').val(),
                    'note_id': {{ $note->id }}
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        });
        @isset($task)
        $('.delete-task').on('click', function(e) {
            $.ajax({
                url: '{{ route('task.delete', $task->id) }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
        });
        $('.edit-task').on('click', function(e) {
            $.redirect({
                url: '{{ route('task', $task->id) }}',
                method: 'GET'
            })
        });
        $('img').on('click', function (e) {
            window.location.href = '{{ url($task->image) }}';
        })
        @endisset
    });
</script>
