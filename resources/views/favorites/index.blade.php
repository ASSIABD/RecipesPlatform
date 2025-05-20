@extends('layouts.main')

@section('corps')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-heart-fill"></i> My Favorite Recipes</h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-heart text-muted" style="font-size: 5rem;"></i>
                    <h3 class="mt-3">No favorite recipes yet</h3>
                    <p class="text-muted mb-4">You haven't added any recipes to your favorites yet.</p>
                    <a href="{{ url('/AllRecipes') }}" class="btn btn-danger">
                        <i class="bi bi-book"></i> Browse Recipes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
