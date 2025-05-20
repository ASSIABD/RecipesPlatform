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
                        // Use the model's image_url accessor which already handles all the path logic
                        $imageUrl = $recipe->image_url;
                        $placeholder = asset('images/placeholder.jpg');
                        
                        // Check if the image exists
                        $imageExists = true;
                        if (empty($recipe->image)) {
                            $imagePath = $placeholder;
                            $imageExists = false;
                        } else {
                            $imagePath = $imageUrl;
                        }
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
                            <span class="text-warning">★★★★☆</span>
                            <small class="text-muted">{{ $recipe->duration ?? 'N/A' }} min</small>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="bi bi-heart toggle-heart text-danger me-1" style="cursor: pointer;"></i>
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

<!-- Script pour changer l'icône -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-heart').forEach(function(icon) {
            icon.addEventListener('click', function () {
                this.classList.toggle('bi-heart');
                this.classList.toggle('bi-heart-fill');
            });
        });
    });
</script>
@endsection
