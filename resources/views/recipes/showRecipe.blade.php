@extends('layouts.navBare')

@section('content')
<div class="container mt-4">

    <!-- Breadcrumb -->
    <nav class="breadcrumb mb-3">
        <span class="breadcrumb-item">Home</span>
        <span class="breadcrumb-item active">Recipes - Grid Layout</span>
    </nav>

    <!-- Search Box -->
    <div class="search-box mb-5">
        <form action="{{ route('recette.index') }}" method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Keywords..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Recipe Cards -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($recipes as $recipe)
        <div class="col">
            <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm">
                    @php
                        $imageUrl = $recipe->image_url;
                        $placeholder = asset('images/placeholder.jpg');
                        $imageExists = !empty($recipe->image);
                        $imagePath = $imageExists ? $imageUrl : $placeholder;

                        $isFavorited = auth()->check() && auth()->user()->favoriteRecipes->contains($recipe->id);
                        $heartClass = $isFavorited ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary';
                    @endphp
                    <div style="height: 160px; overflow: hidden; position: relative; background-color: #f8f9fa;">
                        @if($imageExists)
                        <img src="{{ $imagePath }}" 
                             class="card-img-top w-100 h-100" 
                             style="object-fit: cover;" 
                             alt="{{ $recipe->title }}" 
                             onerror="this.onerror=null; this.src='{{ $placeholder }}'"
                             loading="lazy"
                             referrerpolicy="no-referrer">
                        @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ asset('avatars/avatarInconnue.jpg') }}" alt="{{ $recipe->user->name }}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                            <small class="text-muted">{{ $recipe->user->name }}</small>
                        </div>
                        <h6 class="card-title">{{ $recipe->title }}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            @php
                                $rating = round($recipe->averageRating() * 2) / 2;
                            @endphp
                            <span class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($rating >= $i)
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($rating + 0.5 == $i)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                                <small class="text-muted ms-1">({{ $recipe->ratings()->count() }})</small>
                            </span>
                            <small class="text-muted">{{ $recipe->duration ?? 'N/A' }} min</small>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            {{-- Cœur toggle favori avec data-id et classes dynamiques --}}
                            @if(auth()->check())
                            <i 
                               class="bi toggle-heart me-1 {{ $heartClass }}" 
                               style="cursor: pointer; font-size: 1.2rem;"
                               data-id="{{ $recipe->id }}"
                               title="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                            </i>
                            @else
                            <i class="bi bi-heart text-secondary me-1" style="font-size: 1.2rem;" title="Connectez-vous pour ajouter aux favoris"></i>
                            @endif
                            <small class="text-muted">Save</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-4">
        <button class="btn btn-dark rounded-pill">Show All Recipes</button>
    </div>
</div>

{{-- CSRF token meta pour AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Script AJAX toggle favori --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('.toggle-heart').forEach(function(icon) {
        icon.addEventListener('click', async function (event) {
            event.preventDefault();

            const recipeId = this.getAttribute('data-id');
            const iconElement = this;

            try {
                const response = await fetch(`/recipes/${recipeId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                });

                if (!response.ok) throw new Error('Erreur réseau');

                const data = await response.json();

                if (data.favorited) {
                    iconElement.classList.remove('bi-heart', 'text-secondary');
                    iconElement.classList.add('bi-heart-fill', 'text-danger');
                    iconElement.setAttribute('title', 'Retirer des favoris');
                } else {
                    iconElement.classList.remove('bi-heart-fill', 'text-danger');
                    iconElement.classList.add('bi-heart', 'text-secondary');
                    iconElement.setAttribute('title', 'Ajouter aux favoris');
                }
            } catch (error) {
                console.error('Erreur lors du toggle favori:', error);
                alert('Impossible de modifier les favoris pour le moment.');
            }
        });
    });
});
</script>
@endsection
