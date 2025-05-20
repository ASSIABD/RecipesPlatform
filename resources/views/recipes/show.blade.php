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
            
            <!-- Recipe Meta Overlay -->
            <div class="position-absolute bottom-0 start-0 p-3 w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge bg-danger">
                        <i class="bi bi-folder"></i> {{ $recipe->category->name }}
                    </span>
                    <span class="badge bg-{{ $recipe->difficulty == 'easy' ? 'success' : ($recipe->difficulty == 'medium' ? 'warning' : 'danger') }}">
                        {{ ucfirst($recipe->difficulty) }}
                    </span>
                    <span class="badge bg-secondary">
                        <i class="bi bi-clock"></i> {{ $recipe->duration }} min
                    </span>
                </div>
                
                <!-- Author Info -->
                <div class="d-flex align-items-center">
                    <img src="{{ $recipe->user->avatar_url }}" 
                         alt="{{ $recipe->user->name }}" 
                         class="rounded-circle me-2 border border-2 border-white" 
                         style="width: 40px; height: 40px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ asset('avatars/avatarInconnue.jpg') }}'">
                    <div>
                        <p class="text-white mb-0">By {{ $recipe->user->name }}</p>
                        <p class="text-white-50 mb-0 small">
                            <i class="bi bi-calendar"></i> {{ $recipe->created_at->format('F d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recipe Actions -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <!-- Favorites and comments removed -->
            </div>
            
            @if(Auth::check() && Auth::id() === $recipe->user_id)
                <div class="d-flex gap-2">
                    <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to delete this recipe? This action cannot be undone.')">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
        
        <!-- Recipe Description -->
        @if($recipe->description)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-danger mb-3">
                        <i class="bi bi-journal-text me-2"></i>About This Recipe
                    </h5>
                    <p class="card-text">{{ $recipe->description }}</p>
                </div>
            </div>
        @endif

    <div class="row">
        <!-- Left Column - Ingredients -->
        <div class="col-lg-4">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-danger">
                        <i class="bi bi-list-ul me-2"></i>Ingredients
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                            @if(!empty(trim($ingredient)))
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-check-square text-secondary me-2"></i>
                                    <span>{{ $ingredient }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            

        </div>
        
        <!-- Right Column - Method -->
        <div class="col-lg-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-danger">
                        <i class="bi bi-card-checklist me-2"></i>Method
                    </h5>
                </div>
                <div class="card-body">
                    <ol class="list-unstyled">
                        @php
                            $steps = array_filter(explode("\n", $recipe->steps));
                            $stepNumber = 1;
                        @endphp
                        @foreach($steps as $step)
                            @if(!empty(trim($step)))
                                <li class="mb-3">
                                    <div class="d-flex">
                                        <span class="badge bg-danger rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                              style="width: 28px; height: 28px; flex-shrink: 0;">
                                            {{ $stepNumber }}
                                        </span>
                                        <span>{{ $step }}</span>
                                    </div>
                                </li>
                                @php $stepNumber++; @endphp
                            @endif
                        @endforeach
                    </ol>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-chat-left-text text-danger"></i> Comments</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">No comments yet for this recipe.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection