@extends('layouts.main')
@section('corps')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4><i class="bi bi-person-circle"></i> Update Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="text-center mb-4">
                            <img id="imagePreview" 
                                 src="{{ Auth::user()->avatar_url }}" 
                                 alt="Profile Picture" 
                                 class="rounded-circle img-fluid mb-3" 
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='{{ asset('avatars/avatarInconnue.jpg') }}'"
                                 data-default-src="{{ asset('avatars/avatarInconnue.jpg') }}">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ Auth::user()->name }}" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ Auth::user()->email }}" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Profile Picture</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="avatar" 
                                   name="avatar" 
                                   accept="image/*"
                                   onchange="previewImage(this);">
                            <small class="text-muted">Upload a square image (150x150 recommended)</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const defaultImage = preview.dataset.defaultSrc;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Create a new image element to handle potential errors
            const img = new Image();
            img.onload = function() {
                preview.src = e.target.result;
                // Force a reflow to ensure the browser updates the image
                preview.style.display = 'none';
                preview.offsetHeight; // Trigger reflow
                preview.style.display = 'block';
            };
            img.onerror = function() {
                preview.src = defaultImage;
            };
            img.src = e.target.result;
        };
        
        reader.onerror = function() {
            preview.src = defaultImage;
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = defaultImage;
    }
}
</script>
@endsection

@endsection
