@extends('layouts.main')
@section('corps')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="card-title">Welcome, {{ Auth::user()->name }}!</h2>
                    <p class="card-text">You are now logged in and can add, edit, or delete your recipes.</p>
                    <a href="{{ route('recipes.create') }}" class="btn btn-danger">
                        <i class="bi bi-plus-circle"></i> Add New Recipe
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-journal-richtext"></i> Your Recent Recipes</h4>
                </div>
                <div class="card-body">
                    @if($userRecipes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>title</th>
                                        <th>Category</th>
                                        <th>creation date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userRecipes as $recipe)
                                        <tr>
                                            <td>
                                                <a href="{{ route('recipes.show', $recipe) }}" class="text-decoration-none">
                                                    {{ $recipe->title }}
                                                </a>
                                                @php
                                                    $rating = round($recipe->averageRating() * 2) / 2;
                                                @endphp
                                                <div class="text-warning small">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($rating >= $i)
                                                            <i class="bi bi-star-fill"></i>
                                                        @elseif ($rating + 0.5 == $i)
                                                            <i class="bi bi-star-half"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                    <small class="text-muted ms-1">({{ number_format($recipe->averageRating(), 1) }})</small>
                                                </div>
                                            </td>
                                            <td>{{ $recipe->category->name }}</td>
                                            <td>{{ $recipe->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this recipe?');">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="#" class="btn btn-outline-danger">View All My Recipes</a>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">You haven't added any recipes yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
