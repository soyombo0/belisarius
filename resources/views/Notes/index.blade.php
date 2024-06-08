@extends('navbar')


@section('title', 'Notes')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<div class="d-flex justify-content-center">
    <div class="position-absolute bg-light shadow-lg p-5 rounded-3 border border-2 z-3 w-50 bottom-50" style="display: none">
        <h3>Create Note</h3>
        <form id="storeNote">
            <input class="note-name-input form-control rounded-3 my-1" type="text" name="noteName" placeholder="Enter note name">
            <button class="btn btn-success my-1">Create Note</button>
        </form>
    </div>
    <div class="row">
        <div class="">
            <h3>Notes:</h3>
            @foreach($notes as $note)
                <div class="bg-light rounded-3 row py-2 my-2 text-center">
                    <p class="col-3 ">{{ $note->name }}</p>
                    <a href="/note/{{ $note->id }}" class="edit-note col-3 btn btn-primary mx-3">Edit Note</a>
                    <button class="delete-note col-3 btn btn-danger mx-3">Delete Note</button>
                </div>
            @endforeach
        </div>
        <div class="">
            <button class="create-note btn btn-success my-1">Add Note</button>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.create-note').on('click', function (e) {
           $('.position-absolute').css('display', 'block');
        });
        $('#storeNote').on('submit', function(e) {
            e.preventDefault();
            $('.note-name-input').text('');
            $.ajax({
                url: '{{ route('notes') }}',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('body').html(data);
                    $('.note-name-input').text('');
                }
            });
            $('.position-absolute').css('display', 'none');
        });
        @isset($note)
        $('.edit-note').on('click', function(e) {
            $.redirect({
                url: '{{ route('note', $note->id) }}',
                method: 'GET'
            })
        }),
        $('.delete-note').on('click', function(e) {
            $.ajax({
                url: '{{ route('note.delete', $note->id) }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('body').html(data);
                }
            })
        })
        @endisset
    });
</script>
