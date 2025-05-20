@extends('layouts.main')

@section('corps')

<div class="container py-4">

    {{-- Author Information --}}
    <div class="text-center pt-3 pb-4 mb-5">
        <div class="mb-3">
            <img src="{{ $author->avatar ? asset('storage/' . $author->avatar) : asset('avatars/avatarInconnue.jpg') }}" 
                 alt="{{ $author->name }}'s avatar" 
                 class="img-fluid rounded-circle border border-3 border-light shadow-sm" 
                 style="width: 120px; height: 120px; object-fit: cover;"
                 onerror="this.onerror=null; this.src='{{ asset('avatars/avatarInconnue.jpg') }}'">
        </div>

        <h1 class="h2 fw-bold text-dark mb-1">{{ $author->name }}</h1>
        <p class="text-muted small mb-2">
            Member since: {{ $author->created_at->format('d/m/Y') }}
            <span class="mx-2">|</span>
            {{ $author->recipes_count }} recipe(s) published
        </p>
        @if($author->bio)
            <p class="text-secondary fst-italic" style="max-width: 600px; margin-left: auto; margin-right:auto;">{{ $author->bio }}</p>
        @endif
    </div>

    {{-- Author's Recipes Section --}}
    <section class="mb-5">
        <h2 class="h3 fw-semibold text-danger mb-3 pb-2 border-bottom border-danger border-opacity-25">Recipes by {{ $author->name }}</h2>
        {{-- Note: border-opacity-25 is for Bootstrap 5.1+. In earlier versions, border is fully opaque. --}}
        {{-- Alternative for a subtler border without opacity: border-danger-subtle (Bootstrap 5.3+) --}}

        @if($recipes->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($recipes as $recipe)
                    <div class="col">
                        {{-- For subtle hover effect with Bootstrap, you can toggle shadow-sm and shadow via JS or CSS. --}}
                        {{-- Here we keep shadow-sm for a consistent look. --}}
                        <div class="card h-100 shadow-sm border-light">
                            <a href="{{ route('recipes.show', $recipe->id) }}">
                                @if($recipe->image)
                                    <img src="{{ asset('storage/' . $recipe->image) }}" class="card-img-top" alt="{{ $recipe->title }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/300x200.png?text={{ urlencode($recipe->title) }}" class="card-img-top" alt="{{ $recipe->title }}" style="height: 200px; object-fit: cover;">
                                @endif
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ route('recipes.show', $recipe->id) }}" class="text-dark text-decoration-none stretched-link">{{ $recipe->title }}</a>
                                </h5>
                                <p class="card-text text-muted small">{{ Str::limit($recipe->description, 80) }}</p>
                            </div>
                            <div class="card-footer bg-light border-0">
                                <small class="text-muted">Published on {{ $recipe->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $recipes->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-secondary text-center" role="alert">
                {{ $author->name }} has not published any recipes yet.
            </div>
        @endif
    </section>

    <hr class="my-5 border-secondary border-opacity-25"> {{-- A subtler horizontal line --}}
    {{-- Alternative: border-secondary-subtle (Bootstrap 5.3+) --}}

    {{-- Section: Comments posted by the author --}}
    <section>
        <h2 class="h3 fw-semibold text-danger mb-3 pb-2 border-bottom border-danger border-opacity-25">Comments posted by {{ $author->name }}</h2>

        @if ($comments->isNotEmpty())
            <div class="list-group list-group-flush">
                @foreach ($comments as $comment)
                    {{-- Accent is now a standard Bootstrap border on the left --}}
                    <div class="list-group-item mb-3 p-3 shadow-sm rounded border-start border-danger border-4">
                        {{-- border-4 for thickness, border-start for position, border-danger for color --}}
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">
                                @if ($comment->recipe)
                                    <small class="text-muted">On recipe:</small>
                                    <a href="{{ route('recipes.show', $comment->recipe->id) }}" class="text-dark fw-medium text-decoration-none">{{ $comment->recipe->title }}</a>
                                @else
                                    <span class="text-muted fst-italic">On a recipe that has been deleted</span>
                                @endif
                            </h6>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 mt-2 fst-italic text-secondary">"{{ $comment->content }}"</p>
                        <small class="text-muted">Posted on {{ $comment->created_at->format('d/m/Y \a\t H:i') }}</small>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $comments->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-secondary text-center" role="alert">
                {{ $author->name }} has not posted any comments yet.
            </div>
        @endif
    </section>

</div>
@endsection
