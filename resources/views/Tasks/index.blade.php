@extends('navbar')

@section('content')
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <div class="row">
        <div class="col-6">
            <img src="{{ $task->image }}" width="150" height="150">
            <h3>{{ $task->name }}</h3>
            <form id="storeImage">
                <button class="upload-img  btn btn-primary mx-3">Upload Image</button>
                <button class="delete-img  btn btn-danger mx-3">Delete Image</button>
                <input type="file" name="taskImage" accept="image/jpeg, image/png">
            </form>
            <h3>Tags:</h3>
            @if($tags)
                <div class="row  text-center">
                @foreach($tags as $tag)
                        <p class="col-5 bg-info rounded-3 mx-1 my-2">{{ $tag->name }}</p>
                @endforeach
                </div>
            @endif
        </div>

        <div class="col-6">
            <form id="storeTag">
                <input class="form-control" name="tagName" placeholder="Enter tag's name">
                <button class="add-tag  btn btn-primary mx-3" type="submit">Add Tag</button>
            </form>
        </div>

    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#storeTag').on('submit', function(e) {
            e.preventDefault();
            $('.note-name-input').text('');
            $.ajax({
                url: '{{ route('tags') }}',
                method: 'POST',
                data: {
                    'tagName': $(this).find('input[name="tagName"]').val(),
                    'task_id': {{ $task->id }}
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        });
        $('#storeImage').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('task.image', $task->id) }}',
                type: 'POST',
                data: new FormData($('#storeImage')[0]), // The form with the file inputs.
                processData: false,
                contentType: false,                    // Using FormData, no need to process data.
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
        });
        $('.delete-img').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('task.image.delete', $task->id) }}',
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
        });
        $('img').on('click', function (e) {
            window.location.href = '{{ url($task->image) }}';
        })
    })
</script>
