<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cooking Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #f44336;
        }
        .search-box {
            background-color: #f44336;
            padding: 20px;
            border-radius: 10px;
            color: white;
        }
        .search-box input,
        .search-box select {
            border-radius: 6px;
        }
        .search-box .btn {
            background-color: #444;
            color: white;
        }
        .author-card {
            text-align: center;
            margin-bottom: 20px;
        }
        .author-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .tag-card {
            width: 100%;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .tag-card img {
            width: 100%;
            height: 90px;
            object-fit: cover;
        }
        .tag-card .tag-title {
            background-color: #f44336;
            color: white;
            text-align: center;
            padding: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>


    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="{{ asset('images/cuisine1.png') }}" alt="Logo" style="width: 30px;">  Cook & Share</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavContent" aria-controls="navbarNavContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavContent">
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recette.index') }}">
                            <i class="bi bi-book me-1"></i>Recipes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recipes.index') }}">
                            <i class="bi bi-journal-text me-1"></i>Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auteurs.index') }}">
                            <i class="bi bi-people me-1"></i>Authors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/chatbot') }}">
                            <i class="bi bi-robot me-1"></i>AI Assistant
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="{{ route('add-recipe') }}">
                            <i class="bi bi-plus-circle me-1"></i>Add Recipe
                        </a>
                    </li>
                </ul>

                <!-- Right aligned items -->
                <div class="d-flex align-items-center">
                    <!-- Favorites Link with Count -->
                    @auth
                    <!-- Add New Recipe Button -->
                    <a href="{{ route('recipes.create') }}" class="btn btn-outline-light me-3">
                        <i class="bi bi-plus-circle"></i> New Recipe
                    </a>

                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                $user = Auth::user();
                $avatarUrl = $user->avatar_url; // Use the accessor we just updated
                $defaultAvatar = asset('avatars/avatarInconnue.jpg') . '?v=' . time();
              @endphp
              <img src="{{ $avatarUrl }}" 
                   alt="{{ $user->name }}" 
                   class="rounded-circle me-2" 
                   style="width: 32px; height: 32px; object-fit: cover;"
                   onerror="this.onerror=null; this.src='{{ $defaultAvatar }}'"
                   loading="lazy">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i>My Profile
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}">
                                <i class="bi bi-heart me-2"></i>Favorites
                                <span class="badge bg-danger rounded-pill ms-2">
                                    {{ auth()->user()->favorites()->where('item_type', 'App\Models\Recipe')->count() }}
                                </span>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <!-- Login Button for guests -->
                    <a class="btn btn-outline-light me-2" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENU -->
    <main>
        @yield('content')
    </main>

    @auth
    <script>
        // Function to update favorites count
        function updateFavoritesCount() {
            fetch('/favorites/count')
                .then(response => response.json())
                .then(data => {
                    const countBadge = document.getElementById('favorites-count');
                    if (countBadge && data.success) {
                        countBadge.textContent = data.count;
                    }
                })
                .catch(error => console.error('Error updating favorites count:', error));
        }

        // Update count when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateFavoritesCount();
        });

        // Listen for custom event to update favorites count
        document.addEventListener('favoritesUpdated', updateFavoritesCount);
    </script>
    @endauth
</body>
</html>