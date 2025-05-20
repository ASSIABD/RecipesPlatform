@extends('layouts.main')

@push('styles')
<style>
    .favorite-heart {
        cursor: pointer;
        font-size: 1.5rem;
        transition: color 0.3s ease;
        position: absolute !important;
        z-index: 1000;
    }
    .favorite-heart.active {
        color: #ff4444;
    }

    /* Prevent Bootstrap's default card click behavior */
    .card.no-click {
        pointer-events: none;
    }
    .card.no-click .favorite-heart {
        pointer-events: auto;
    }
    .card.no-click .card-footer {
        pointer-events: auto;
    }

    /* Custom pagination styles */
    .pagination {
        margin: 2rem 0;
    }
    .page-link {
        color: #dc3545;
        border-color: #dc3545;
    }
    .page-link:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    .page-item.active .page-link {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
    }

    /* Latest Recipes Section Styles */
    .recipe-card {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: transform 0.2s ease-in-out;
        width: 100%;
        position: relative;
    }

    .recipe-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .recipe-card .card-img-top {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }

    .recipe-card .card-body {
        padding: 15px;
    }

    .recipe-title-main {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }

    .author-avatar-bottom {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }

    .section-title {
        color: #f44336;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .section-subtitle {
        font-size: 1rem;
        line-height: 1.5;
    }
</style>
@endpush

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to heart icons
    const hearts = document.querySelectorAll('.favorite-heart');
    hearts.forEach(heart => {
        heart.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle the heart icon
            this.classList.toggle('fa-solid');
            this.classList.toggle('fa-regular');
            
            // Get the recipe ID from data attribute
            const recipeId = this.dataset.recipeId;
            
            // Send AJAX request to toggle favorite
            fetch('/toggle-favorite/' + recipeId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Add a global click handler to prevent navigation when clicking heart
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('favorite-heart')) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
});
</script>
@endsection

@section('corps')
<div class="container">


    <!-- Main Recipes Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-journal-richtext"></i> My Recipes</h2>
        <a href="{{ route('recipes.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-circle"></i> Add New Recipe
        </a>
    </div>
    
    @if(request()->has('search') || request()->has('category'))
        <div class="alert alert-info mb-4">
            <i class="bi bi-funnel"></i> Filtered results 
            @if(request('search'))
                for: <strong>{{ request('search') }}</strong>
            @endif
            @if(request('category'))
                @php $category = \App\Models\Category::find(request('category')); @endphp
                @if($category)
                    in category: <strong>{{ $category->name }}</strong>
                @endif
            @endif
            <a href="{{ route('recipes.index') }}" class="float-end">Clear filters</a>
        </div>
    @endif
    
    @if(count($recipes) > 0)
        <div class="row">
            @foreach($recipes as $recipe)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm no-click">
                        <div class="position-relative">
                            @php
                                $imagePath = $recipe->image ? (
                                    str_starts_with($recipe->image, 'http') ? 
                                    $recipe->image : 
                                    (str_starts_with($recipe->image, 'storage/') ? 
                                        asset($recipe->image) : 
                                        asset('storage/' . $recipe->image)
                                    )
                                ) : 'https://via.placeholder.com/300x200?text=No+Image';
                            @endphp
                            <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $recipe->title }}" style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-{{ $recipe->difficulty == 'easy' ? 'success' : ($recipe->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($recipe->difficulty) }}
                                </span>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('avatars/avatarInconnue.jpg') }}" alt="{{ $recipe->user->name }}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                <div>
                                    <h5 class="card-title mb-0">{{ $recipe->title }}</h5>
                                    <p class="card-text text-muted small mb-0">
                                        <i class="bi bi-folder"></i> {{ $recipe->category->name }} |
                                        <i class="bi bi-clock"></i> {{ $recipe->duration }} min
                                    </p>
                                </div>
                            </div>
                            <p class="card-text mt-2">{{ Str::limit($recipe->description, 100) }}</p>
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-heart toggle-heart text-danger me-1" style="cursor: pointer;"></i>
                                <small class="text-muted">Save</small>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 text-center">
                            <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-eye"></i> View Recipe
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $recipes->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No recipes found. <a href="{{ route('recipes.create') }}" class="alert-link">Be the first to add one!</a></p>
        </div>
    @endif
</div>
@endsection