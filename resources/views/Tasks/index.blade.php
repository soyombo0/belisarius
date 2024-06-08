@extends('navbar')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<div class="row">
    <div class="col-2">
        <div class="">
            <img src="{{ $task->image }}" width="150" height="150">
        </div>
        <h3>{{ $task->name }}</h3>
        <button class="create-img  btn btn-primary">Upload Image</button>
    </div>
    <div class="col-10 d-flex justify-content-center">
        <div class="">
            <button class="create-tag add-tag  btn btn-primary" type="submit">Add Tag</button>
            <h3>Tags:</h3>
            @if($tags)
                @foreach($tags as $tag)
                    <div class="row text-center">
                        <p class="bg-dark text-white rounded-3 my-2 py-1">{{ $tag->name }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="tag-store position-absolute bg-light shadow-lg p-5 rounded-3 border border-2 z-3 w-50 bottom-50" style="display: none">
        <h3>Create Tag</h3>
        <form id="storeTag">
            <input class="store-tag form-control my-2" name="tagName" placeholder="Enter tag's name">
            <button class="add-tag  btn btn-primary my-2" type="submit">Add Tag</button>
        </form>
    </div>
    <div class="image-upload position-absolute bg-light shadow-lg p-5 rounded-3 border border-2 z-3 w-50 bottom-50" style="display: none">
        <h3>Upload Image</h3>
        <form id="storeImage">
            <button class="upload-img  btn btn-primary mx-3">Upload Image</button>
            <input type="file" name="taskImage" accept="image/jpeg, image/png">
            <button class="delete-img  btn btn-danger mx-3">Delete Image</button>
        </form>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.create-tag').on('click', function (e) {
            $('.tag-store').css('display', 'block');
        });
        $('.create-img').on('click', function (e) {
            $('.image-upload').css('display', 'block');
        });
        $('#storeTag').on('submit', function(e) {
            e.preventDefault();
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
                success: function (data) {
                    $('.store-tag').text('');
                    $('body').html(data);
                }
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
                success: function (data) {
                    $('body').html(data);
                }
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
        @isset($task->image)
        $('img').on('click', function (e) {
            window.location.href = '{{ url($task->image) }}';
        })
        @endisset
    })
</script>
