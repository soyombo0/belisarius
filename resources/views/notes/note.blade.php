@extends('navbar')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<h3>Note's name: {{ $note->name }}</h3>
<button class="share-note btn btn-success my-1">Share Note</button>

<div class="d-flex justify-content-center">

    <div id="addTask" class="position-absolute bg-light shadow-lg p-5 rounded-3 border border-2 z-3 w-50 bottom-50" style="display: none">
        <h3>Create Task</h3>
        <form id="storeTask">
            <input class="store-task col-6 form-control  rounded-3 my-1" type="text" name="taskName" placeholder="enter task's name">
            <button class="col-6 delete-note col-3 btn btn-success my-1" type="submit">Add Task</button>
        </form>
    </div>

    <div id="share-note" class="bg-light shadow-lg p-5 rounded-3 position-absolute border border-2 z-3 w-50 bottom-50" style="display: none">
        <h3>Create Note</h3>
        <form id="shareNote">
            <select class="form-select">
                @foreach($users as $user)
                    <option name="userId" value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-success my-2">Share Note</button>
{{--            <label for="userCanEdit">Allow user to edit</label>--}}
{{--            <input name="userCanEdit" type="checkbox" id="userCanEdit">--}}
        </form>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="row">
                <form class="search-task">
                    <input class="form-control rounded-3 my-1" type="text" name="noteSearch" placeholder="Enter task's name">
                    <div class="">
                        <label for="tags-name">Tags</label>
                        <select id="tags-name" class="form-select">
                            @foreach($allTags as $tag)
                                <option name="tagNames" value="{{ $tag->name }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="add-tag btn btn-success my-1">Add Tag To Search</button>
                        <p id="selected-tags"></p>
                    </div>
                    <button type="submit" class="btn btn-primary my-1">Find Task</button>
                </form>
            </div>
        </div>
        <div class="col-6">
            <button class="create-task col-6 delete-note col-3 btn btn-success" type="submit">Add Task</button>
        </div>
        <h3>Tasks:</h3>
        @foreach($tasks as $task)
            <div class="bg-light rounded-3 py-2 my-2 text-center row">
                <div class="col-4">
                    <img src="{{ $task->image }}" width="150" height="150">
                    <p class=" ">{{ $task->name }}</p>
                </div>
                <div class="col-4 text-center">
                    <p>Tags:</p>
                    @foreach($task->tags as $tag)
                        <p class="bg-dark text-white rounded-3 mx-5 my-2 py-1">{{ $tag->name }}</p>
                    @endforeach
                </div>
                <div class="col-4">
                    <a href="/task/{{ $task->id }}" class="edit-task  btn btn-primary mx-3">Edit Task</a>
                    <button class="delete-task  btn btn-danger mx-3">Delete Task</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.share-note').on('click', function (e) {
            $('#share-note').css('display', 'block');
        });
        $('#shareNote').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('note.share', $note->id) }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'user_id': $(this).find('select[id="shareUser"]').val(),
                    'note_id': {{ $note->id }},
                    'userCanEdit': $(this).find('input[id="userCanEdit"]').val(),
                },
                success: function (data) {
                    $('body').html(data);
                }
            })
        }),
        $('.create-task').on('click', function (e) {
            $('#addTask').css('display', 'block');
        });
        $('.add-tag').on('click', function() {
            var selectedTag = $('#tags-name').val();
            $('#selected-tags').append(selectedTag + ' ');
        });
        $('.search-task').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('tasks.search.tags') }}',
                method: 'POST',
                data: {
                    'taskName': $(this).find('input[name="taskName"]').val(),
                    'tagNames': $(this).find('p[id="selected-tags"]').text().trim().split(' '),
                    'note_id': {{ $note->id }}
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('body').html(data);
                }
            });
        });
        $('#storeTask').on('submit', function(e) {
            e.preventDefault();
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
                success: function (data) {
                    $('.store-task').text('');
                    $('body').html(data);
                }
            });
        });
        @isset($task)
        $('.delete-task').on('click', function(e) {
            $.ajax({
                url: '{{ route('task.delete', $task->id) }}',
                method: 'DELETE',
                data: {
                    'note_id': {{ $note->id }}
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('body').html(data);
                }
            })
        });
        $('.edit-task').on('click', function(e) {
            $.redirect({
                url: '{{ route('task', $task->id) }}',
                method: 'GET'
            })
        });
        @isset($task->image)
            $('img').on('click', function (e) {
                window.location.href = '{{ url($task->image) }}';
            })
        @endisset
        @endisset
    });
</script>
