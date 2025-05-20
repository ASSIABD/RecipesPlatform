<!-- Profile Page -->
@extends('layouts.main')
@section('corps')
<div class="container">
    <!-- Profile Header Banner -->
    <div class="card mb-4">
        <div class="card-body bg-danger text-white p-5">
            <div class="d-flex align-items-center">
                <div class="me-4">
                    <img src="{{ asset('avatars/avatarInconnue.jpg') }}"
                         alt="{{ Auth::user()->name }}" 
                         class="rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                </div>
                <div>
                    <h2 class="mb-1 fw-bold">{{ Auth::user()->name }}</h2>
                    <p class="mb-0 text-white-50">Joined {{ Auth::user()->created_at->format('M Y') }}</p>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('profile.edit') }}" class="btn btn-danger">
                        <i class="bi bi-pencil me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row g-4 mb-4">
        <!-- Recipes Shared -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-3">Recipes Shared</h5>
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="display-4 text-dark fw-bold">{{ $recipes->total() }}</div>
                    </div>
                    <a href="{{ route('recipes.index') }}" class="text-decoration-none">
                        <div class="d-flex align-items-center text-dark">
                            <i class="bi bi-eye me-2"></i><span class="fw-bold">View All</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- My Latest Recipes -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">My Latest Recipes</h3>
            @if($recipes->count() > 0)
                <div class="row g-4">
                    @foreach($recipes as $recipe)
                        <div class="col-md-4">
                            <div class="card h-100">
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
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this recipe?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $recipes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bookmark-plus display-3 text-danger mb-3"></i>
                    <h4 class="mb-3">No Recent Recipes</h4>
                    <p class="text-muted mb-4">You haven't shared any recipes yet.</p>
                    <a href="{{ route('recipes.create') }}" class="btn btn-danger">
                        <i class="bi bi-plus-circle me-2"></i>Share Your First Recipe
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
