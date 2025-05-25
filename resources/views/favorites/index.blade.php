@extends('layouts.main')

@section('corps')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-heart-fill me-2"></i> my favorites</h4>
                    <a href="{{ url('/AllRecipes') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-book me-1"></i> Browse recipes
                    </a>
                </div>

                @if ($favorites->isEmpty())
                    <div class="card-body text-center py-5">
                        <i class="bi bi-heart text-muted" style="font-size: 5rem;"></i>
                        <h3 class="mt-3">No favorite recipe</h3>
                        <p class="text-muted mb-4">You have not yet added any recipes to your favorites.</p>
                        <a href="{{ url('/AllRecipes') }}" class="btn btn-danger">
                            <i class="bi bi-book"></i> Discover recipes
                        </a>
                    </div>
                @else
                    <div class="card-body">
                        <div class="row">
                            @foreach ($favorites as $recipe)
                                <div class="col-md-4">
                                    <div class="card mb-4 shadow-sm h-100">
                                        @php
                                            $imagePath = $recipe->image_url ?? asset('images/placeholder.jpg');
                                        @endphp
                                        <img src="{{ $imagePath }}" 
                                             alt="{{ $recipe->title }}" 
                                             class="card-img-top" 
                                             style="height: 180px; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}'">
                                        
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $recipe->title }}</h5>
                                            
                                            <div class="mt-auto">
                                                <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary btn-sm me-2">
                                                    Voir la recette
                                                </a>

                                                <form method="POST" action="{{ route('favorites.destroy', $recipe->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-x-circle"></i> Withdraw
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
