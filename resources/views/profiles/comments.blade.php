
@extends('layouts.main')

@section('corps')
    <div class="container">
        <h1>Comments by {{ $author->name }}</h1>

        @if ($comments->count() > 0)
            <ul class="list-group">
                @foreach ($comments as $comment)
                    <li class="list-group-item">
                        <p>{{ $comment->content }}</p>
                        <small class="text-muted">
                            Posted by {{ $comment->user->name }} on {{ $comment->created_at->format('d/m/Y') }}
                            on the recipe:
                            @if ($comment->recipe)
                                <a href="{{ route('recipes.show', $comment->recipe->id) }}">{{ $comment->recipe->title }}</a>
                            @else
                                Deleted recipe
                            @endif
                        </small>
                    </li>
                @endforeach
            </ul>
        @else
            <p>This author has not posted any comments yet.</p>
        @endif
    </div>
@endsection
