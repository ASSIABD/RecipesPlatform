@extends('layouts.navBare')

@section('content')
<div class="container-fluid mt-5">
    <!-- Profile Header -->
    <div class="search-box mb-4">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-4">
                    <div class="avatar-container">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) . ($user->avatar_cache ? '?v=' . $user->avatar_cache : '') : asset('avatars/avatarInconnue.jpg') }}" 
                             alt="{{ $user->name }}'s avatar" 
                             class="rounded-circle" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f44336;"
                             onerror="this.onerror=null; this.src='{{ asset('avatars/avatarInconnue.jpg') }}'">
                    </div>
                </div>
                <div>
                    <h2 class="mb-1 text-white">{{ $user->name }}</h2>
                    <p class="text-white-50 mb-0">Joined {{ $user->created_at->format('M Y') }}</p>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary px-4">
                        <i class="bi bi-pencil me-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Stats -->
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Recipes Card -->
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <h6 class="card-title mb-3 text-muted">Recipes Shared</h6>
                        <div class="display-4 mb-3 text-dark">{{ $totalRecipes }}</div>
                        <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-outline-dark px-4">
                            <i class="bi bi-eye me-1"></i>View All
                        </a>
                    </div>
                </div>
            </div>

            <!-- Favorites Card -->
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <h6 class="card-title mb-3 text-muted">Favorites</h6>
                        <div class="display-4 mb-3 text-dark">{{ $totalFavorites }}</div>
                        <a href="{{ route('favorites.index') }}" class="btn btn-sm btn-outline-dark px-4">
                            <i class="bi bi-heart me-1"></i>View All
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <h6 class="card-title mb-3 text-muted">Site Categories</h6>
                        <div class="display-4 mb-3 text-dark">{{ $totalCategories }}</div>
                        <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-outline-dark px-4">
                            <i class="bi bi-grid me-1"></i>View All Recipes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Recipes -->
        @if($recentRecipes->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Recent Recipes</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($recentRecipes as $recipe)
                            <div class="col-12">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-1">{{ $recipe->title }}</h6>
                                                <small class="text-muted">{{ $recipe->created_at->format('M d, Y') }}</small>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="btn-group">
                                                    <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-sm btn-outline-primary px-2">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2" onclick="return confirm('Are you sure you want to delete this recipe?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <p class="text-muted mb-0">No recent recipes yet.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
