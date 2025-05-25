@extends('layouts.main')

@section('title', $recipe->title)

@section('corps')
<section class="recipe-container mt-4">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('recette.index') }}" class="text-decoration-none">Recipes</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($recipe->title, 30) }}</li>
            </ol>
        </nav>

        <!-- Recipe Title -->
        <h1 class="mb-3">{{ $recipe->title }}</h1>

        <!-- Average Rating -->
        @php
            $averageRating = $recipe->averageRating();
            $ratingCount = $recipe->ratings()->count();
        @endphp
        @if($ratingCount > 0)
            <p class="text-warning fs-5 mb-4">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($averageRating >= $i)
                        <i class="bi bi-star-fill"></i>
                    @elseif ($averageRating + 0.5 >= $i)
                        <i class="bi bi-star-half"></i>
                    @else
                        <i class="bi bi-star"></i>
                    @endif
                @endfor
                <small class="text-muted ms-2">({{ number_format($averageRating, 1) }} / 5 from {{ $ratingCount }} ratings)</small>
            </p>
        @else
            <p class="text-muted mb-4">No ratings yet.</p>
        @endif

        <!-- Recipe Image -->
        @php
            $imageUrl = $recipe->image_url;
            $placeholder = asset('images/placeholder.jpg');
            $hasImage = !empty($recipe->image);
        @endphp

        <div class="position-relative mb-4" style="height: 400px; overflow: hidden; border-radius: 8px;">
            @if($hasImage)
                <img src="{{ $imageUrl }}" 
                     class="img-fluid w-100 h-100" 
                     style="object-fit: cover;" 
                     alt="{{ $recipe->title }}" 
                     onerror="this.onerror=null; this.src='{{ $placeholder }}'" 
                     loading="lazy">
            @else
                <div class="bg-light d-flex flex-column justify-content-center align-items-center w-100 h-100">
                    <i class="bi bi-camera text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-2">No image available</p>
                </div>
            @endif

            <!-- Overlay Infos -->
            <div class="position-absolute bottom-0 start-0 p-3 w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge bg-danger"><i class="bi bi-folder"></i> {{ $recipe->category->name }}</span>
                    <span class="badge bg-{{ $recipe->difficulty == 'easy' ? 'success' : ($recipe->difficulty == 'medium' ? 'warning' : 'danger') }}">
                        {{ ucfirst($recipe->difficulty) }}
                    </span>
                    <span class="badge bg-secondary"><i class="bi bi-clock"></i> {{ $recipe->duration }} min</span>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{ $recipe->user->avatar_url }}" 
                         alt="{{ $recipe->user->name }}" 
                         class="rounded-circle me-2 border border-2 border-white" 
                         style="width: 40px; height: 40px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ asset('avatars/avatarInconnue.jpg') }}'">
                    <div>
                        <p class="text-white mb-0">By {{ $recipe->user->name }}</p>
                        <p class="text-white-50 mb-0 small"><i class="bi bi-calendar"></i> {{ $recipe->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit/Delete buttons -->
        @if(Auth::check() && Auth::id() === $recipe->user_id)
            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        @endif

        <!-- Recipe Description -->
        @if($recipe->description)
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-danger"><i class="bi bi-journal-text me-2"></i> About This Recipe</h5>
                    <p>{{ $recipe->description }}</p>
                </div>
            </div>
        @endif

        <!-- Ingredients & Method -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="text-danger"><i class="bi bi-list-ul me-2"></i>Ingredients</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                                @if(trim($ingredient) !== '')
                                    <li class="mb-2"><i class="bi bi-check-square text-secondary me-2"></i>{{ $ingredient }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            

            <div class="col-lg-8">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="text-danger"><i class="bi bi-card-checklist me-2"></i>Method</h5>
                    </div>
                    <div class="card-body">
                        <ol class="list-unstyled">
                            @foreach(array_filter(explode("\n", $recipe->steps)) as $index => $step)
                                <li class="mb-3 d-flex">
                                    <span class="badge bg-danger rounded-circle me-3" style="width: 28px; height: 28px;">{{ $index + 1 }}</span>
                                    <span>{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card mt-4 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-star text-danger"></i> Review & Rating</h5>
                    </div>
                    <div class="card-body">
                        @forelse ($recipe->comments as $comment)
                            <div class="mb-4 border-bottom pb-3">
                                <div class="d-flex align-items-start mb-2">
                                    <img src="{{ asset('avatars/avatarInconnue.jpg') }}" class="rounded-circle me-3" width="40" height="40" alt="User Avatar">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $comment->user->name }}</strong>
                                            <small class="text-muted">Date: <span class="text-danger">{{ $comment->created_at ? $comment->created_at->format('d/m/Y') : 'Unknown' }}</span></small>
                                        </div>
                                        <span class="text-warning">★★★★☆</span>
                                    </div>
                                </div>
                                <p class="mb-0">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <p class="text-muted">No comments yet for this recipe.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Comment Submission Form -->
                    <form action="{{ route('comments.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">

                        <div class="mb-4">
                            <label for="comment" class="form-label">Your Comment:</label>
                            <textarea name="content" id="comment" rows="4" class="form-control" required placeholder="Share your thoughts or tips about this recipe..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-outline-danger">Submit Comment</button>
                    </form>

                <!-- Rating & Comment Form -->
                @if(Auth::check())
                    <form action="{{ route('ratings.store') }}" method="POST" class="mt-4" id="rating-form">
                        @csrf
                        <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                        <input type="hidden" name="rating" id="rating-value" value="0" required>

                        <label class="form-label d-block mb-2">Your Rating:</label>
                        <div id="star-rating" class="mb-3" style="font-size: 1.5rem; cursor: pointer;">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star" data-value="{{ $i }}"></i>
                            @endfor
                        </div>

                        <button type="submit" class="btn btn-danger" disabled id="submit-rating-btn">Submit Rating</button>
                    </form>

                    

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const stars = document.querySelectorAll('#star-rating i');
                            const ratingInput = document.getElementById('rating-value');
                            const submitBtn = document.getElementById('submit-rating-btn');
                            let currentRating = 0;

                            stars.forEach(star => {
                                star.addEventListener('mouseover', () => {
                                    const val = parseInt(star.getAttribute('data-value'));
                                    highlightStars(val);
                                });

                                star.addEventListener('mouseout', () => {
                                    highlightStars(currentRating);
                                });

                                star.addEventListener('click', () => {
                                    currentRating = parseInt(star.getAttribute('data-value'));
                                    ratingInput.value = currentRating;
                                    submitBtn.disabled = currentRating === 0;
                                    highlightStars(currentRating);
                                });
                            });

                            function highlightStars(rating) {
                                stars.forEach(star => {
                                    const starVal = parseInt(star.getAttribute('data-value'));
                                    if (starVal <= rating) {
                                        star.classList.remove('bi-star');
                                        star.classList.add('bi-star-fill', 'text-warning');
                                    } else {
                                        star.classList.add('bi-star');
                                        star.classList.remove('bi-star-fill', 'text-warning');
                                    }
                                });
                            }
                        });
                    </script>
                @else
                    <p class="mt-4 text-muted">Please <a href="{{ route('login') }}">log in</a> to rate and comment on this recipe.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
