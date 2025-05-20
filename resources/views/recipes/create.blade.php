@extends('layouts.main')

@section('corps')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h4><i class="bi bi-plus-circle"></i> Add New Recipe</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Recipe Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Cooking Time (minutes)</label>
                            <input type="number" class="form-control" id="duration" name="duration" value="{{ old('duration') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="difficulty" class="form-label">Difficulty Level</label>
                            <select class="form-select" id="difficulty" name="difficulty" required>
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Recipe Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <small class="text-muted">Upload an appetizing image of your dish</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Brief Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="ingredients" class="form-label">Ingredients</label>
                    <textarea class="form-control" id="ingredients" name="ingredients" rows="5" required placeholder="List your ingredients here, one per line">{{ old('ingredients') }}</textarea>
                    <small class="text-muted">List each ingredient on a new line with quantities</small>
                </div>

                <div class="mb-3">
                    <label for="steps" class="form-label">Preparation Steps</label>
                    <textarea class="form-control" id="steps" name="steps" rows="7" required placeholder="Explain the preparation steps">{{ old('steps') }}</textarea>
                    <small class="text-muted">Provide clear, step-by-step instructions</small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger btn-lg"><i class="bi bi-save"></i> Save Recipe</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection