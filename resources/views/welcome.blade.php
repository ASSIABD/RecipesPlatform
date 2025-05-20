<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cook & Share</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .hero-section {
            position: relative;
            min-height: 600px;
            background-image: url('https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1920&h=1080&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            overflow: hidden;
            transition: transform 0.5s ease;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%);
            z-index: 1;
            transition: opacity 0.3s ease;
        }

        .hero-section:hover {
            transform: scale(1.02);
        }

        .hero-section:hover::before {
            opacity: 0.9;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding-top: 0;
            padding-bottom: 120px;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }

        .hero-content h1 {
            font-family: 'Raleway', sans-serif;
            font-size: 4.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
            line-height: 1.2;
            background: linear-gradient(135deg, #fff, rgba(255, 255, 255, 0.9));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textGlow 2s ease-in-out infinite;
        }

        .hero-content p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem;
            margin-bottom: 40px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            font-weight: 400;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-content .btn-browse-now {
            padding: 18px 50px;
            font-size: 1.2rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            border: 2px solid white;
            color: white;
            border-radius: 35px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .hero-content .btn-browse-now::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .hero-content .btn-browse-now:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-content .btn-browse-now:hover::after {
            left: 100%;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes textGlow {
            0%, 100% {
                text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
            }
            50% {
                text-shadow: 2px 2px 12px rgba(0, 0, 0, 0.6);
            }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .hero-content h1 {
                font-size: 4rem;
            }
            .hero-content p {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 3.5rem;
            }
            .hero-content p {
                font-size: 1.3rem;
            }
            .hero-content .btn-browse-now {
                padding: 15px 40px;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 500px;
                padding-top: 80px;
                padding-bottom: 80px;
            }
            .hero-content h1 {
                font-size: 3rem;
            }
            .hero-content p {
                font-size: 1.2rem;
            }
            .hero-content .btn-browse-now {
                padding: 14px 35px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                min-height: 400px;
            }
            .hero-content h1 {
                font-size: 2.5rem;
            }
            .hero-content p {
                font-size: 1.1rem;
            }
            .hero-content .btn-browse-now {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        .latest-recipes-section {
            padding-top: 40px;
            padding-bottom: 50px;
            background-color: #f8f9fa;
        }
        .latest-recipes-section h2 {
            color: #f44336;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .latest-recipes-section .section-description {
            text-align: center;
            color: #6c757d;
            margin-bottom: 40px;
            font-size: 0.9rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .recipe-card {
            border: none; 
            border-radius: 8px; 
            overflow: hidden; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem; 
            background: white;
            display: flex;
            flex-direction: column;
        }

        .recipe-card:hover {
            transform: scale(1.02); 
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
        }

        .recipe-card img.card-img-top { 
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 8px; 
            border-top-right-radius: 8px;
            transition: transform 0.3s ease;
        }

        .card-body {
            flex: 1;
            padding: 1rem; 
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
        }

        .recipe-card .card-title { 
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .recipe-card .card-title a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .recipe-card .card-title a:hover {
            text-decoration: underline;
            color: #f44336;
        }

        .recipe-card .author-info {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: #555;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        .recipe-card .author-info img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
        }

        @media (max-width: 767.98px) {
            .hero-content {
                text-align: center;
                padding-top: 40px;
                padding-bottom: 40px;
            }
            .hero-content h1 {
                font-size: 2.5rem;
            }
            .hero-content h2 {
                font-size: 1.8rem;
            }
            .hero-image-container {
                display: none;
            }
            .categories-section .col-md-2 {
                flex: 0 0 auto;
                width: 50%;
            }
            .recipe-card .card-title {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    @include('layouts.navBare')

    <!-- HERO SECTION -->
    <style>
        .hero-section {
            position: relative;
            min-height: 500px;
            background-image: url('https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1920&h=1080&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            overflow: hidden;
            transition: transform 0.5s ease;
            margin-top: 0;
            padding-top: 120px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%);
            z-index: 1;
            transition: opacity 0.3s ease;
        }

        .hero-section:hover {
            transform: scale(1.02);
        }

        .hero-section:hover::before {
            opacity: 0.9;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding-bottom: 120px;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }

        .hero-content h1 {
            font-family: 'Raleway', sans-serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
            line-height: 1.2;
            background: linear-gradient(135deg, #fff, rgba(255, 255, 255, 0.9));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textGlow 2s ease-in-out infinite;
        }

        .hero-content p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            margin-bottom: 40px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            font-weight: 400;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-content .btn-browse-now {
            padding: 18px 50px;
            font-size: 1.2rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            border: 2px solid white;
            color: white;
            border-radius: 35px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .hero-content .btn-browse-now::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .hero-content .btn-browse-now:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-content .btn-browse-now:hover::after {
            left: 100%;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes textGlow {
            0%, 100% {
                text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
            }
            50% {
                text-shadow: 2px 2px 12px rgba(0, 0, 0, 0.6);
            }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .hero-content h1 {
                font-size: 3.5rem;
            }
            .hero-content p {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 3rem;
            }
            .hero-content p {
                font-size: 1.2rem;
            }
            .hero-content .btn-browse-now {
                padding: 15px 40px;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 400px;
                padding-top: 80px;
                padding-bottom: 80px;
            }
            .hero-content h1 {
                font-size: 2.5rem;
            }
            .hero-content p {
                font-size: 1.1rem;
            }
            .hero-content .btn-browse-now {
                padding: 14px 35px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                min-height: 300px;
            }
            .hero-content h1 {
                font-size: 2rem;
            }
            .hero-content p {
                font-size: 1rem;
            }
            .hero-content .btn-browse-now {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        .latest-recipes-section {
            padding-top: 40px;
            padding-bottom: 50px;
            background-color: #f8f9fa;
        }

        .latest-recipes-section h2 {
            color: #f44336;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .latest-recipes-section .section-description {
            text-align: center;
            color: #6c757d;
            margin-bottom: 40px;
            font-size: 0.9rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .recipe-card {
            border: none; 
            border-radius: 8px; 
            overflow: hidden; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem; 
            background: white;
            display: flex;
            flex-direction: column;
        }

        .recipe-card:hover {
            transform: scale(1.02); 
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
        }

        .recipe-card img.card-img-top { 
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 8px; 
            border-top-right-radius: 8px;
            transition: transform 0.3s ease;
        }

        .card-body {
            flex: 1;
            padding: 1rem; 
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
        }

        .recipe-card .card-title { 
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .recipe-card .card-title a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .recipe-card .card-title a:hover {
            text-decoration: underline;
            color: #f44336;
        }

        .recipe-card .author-info {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: #555;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        .recipe-card .author-info img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
        }

        @media (max-width: 767.98px) {
            .hero-content {
                text-align: center;
                padding-top: 40px;
                padding-bottom: 40px;
            }
            .hero-content h1 {
                font-size: 2rem;
            }
            .hero-content h2 {
                font-size: 1.5rem;
            }
            .hero-image-container {
                display: none;
            }
            .categories-section .col-md-2 {
                flex: 0 0 auto;
                width: 50%;
            }
            .recipe-card .card-title {
                font-size: 1rem;
            }
        }
    </style>
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Cook & Share</h1>
                <p>Discover and share amazing recipes with the cooking community</p>
                <a href="{{ route('recette.index') }}" class="btn btn-browse-now">
                    <i class="fas fa-utensils"></i>
                    Browse Now
                </a>
            </div>
        </div>
    </section>

    <!-- LATEST RECIPES SECTION -->
    <section class="latest-recipes-section">
        <div class="container">
            <h2>Latest Recipes</h2>
            <p class="section-description">
                Discover our latest culinary creations.
            </p>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mt-4">
                @foreach($latestRecipes as $recipe)
                <div class="col">
                    <a href="{{ route('recipes.show', $recipe) }}" class="text-decoration-none text-dark">
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
                                    <img src="{{ $recipe->user->avatar_url }}" 
                                         class="rounded-circle me-2" 
                                         width="30" 
                                         height="30" 
                                         alt="{{ $recipe->user->name }}" 
                                         style="object-fit: cover;"
                                         onerror="this.onerror=null; this.src='{{ asset('avatars/avatarInconnue.jpg') }}'">
                                    <small class="text-muted">{{ $recipe->user->name }}</small>
                                </div>
                                <h6 class="card-title">{{ $recipe->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    @php
    $rating = round($recipe->averageRating() * 2) / 2; // round to nearest 0.5
@endphp
<div class="text-warning">
    @for ($i = 1; $i <= 5; $i++)
        @if ($rating >= $i)
            <i class="bi bi-star-fill"></i>
        @elseif ($rating + 0.5 == $i)
            <i class="bi bi-star-half"></i>
        @else
            <i class="bi bi-star"></i>
        @endif
    @endfor
</div>

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
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p> {{ date('Y') }} Cook & Share. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // The JavaScript for toggling the heart icon may no longer be needed for these specific cards
        // as the heart icon has been removed from their footers.
        // However, it's kept here in case it's used elsewhere on the page or if you decide to reintroduce favorites.
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listener to all heart icons with class .save-btn-icon
            // (Updated selector to be more specific if you keep the JS)
            document.querySelectorAll('.save-btn i.fa-heart').forEach(function(heart) { // Adjusted selector if it's still FontAwesome
                const form = heart.closest('form');
                if (!form) return; // If heart is not in a form, skip

                heart.addEventListener('click', async function(e) {

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // The JavaScript for toggling the heart icon may no longer be needed for these specific cards
            // as the heart icon has been removed from their footers.
            // However, it's kept here in case it's used elsewhere on the page or if you decide to reintroduce favorites.
            document.addEventListener('DOMContentLoaded', function() {
                // Add click event listener to all heart icons with class .save-btn-icon
                // (Updated selector to be more specific if you keep the JS)
                document.querySelectorAll('.save-btn i.fa-heart').forEach(function(heart) { // Adjusted selector if it's still FontAwesome
                    const form = heart.closest('form');
                    if (!form) return; // If heart is not in a form, skip

                    heart.addEventListener('click', async function(e) {
                        e.stopPropagation(); 
                        e.preventDefault(); 

                        // Check for CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            console.error('CSRF token not found!');
                            return;
                        }

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken.content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json' // Good practice
                                },
                                // body: JSON.stringify({}) // If you need to send data
                            });

                            if (!response.ok) { // Check for HTTP errors
                                const errorData = await response.json().catch(() => ({ message: 'Unknown error occurred' }));
                                console.error('Error status:', response.status, 'Message:', errorData.message);
                                // Optionally revert icon state here
                                return;
                            }
                            
                            const data = await response.json();

                            if (data.success) {
                                if (data.favorited) {
                                    heart.classList.remove('text-secondary');
                                    heart.classList.add('text-danger');
                                    heart.classList.remove('far'); // If using FA for empty
                                    heart.classList.add('fas');   // If using FA for filled
                                } else {
                                    heart.classList.remove('text-danger');
                                    heart.classList.add('text-secondary');
                                    heart.classList.remove('fas'); // If using FA for filled
                                    heart.classList.add('far');   // If using FA for empty
                                }
                            } else {
                                console.error('Toggle favorite error:', data.message);
                            }
                        } catch (error) {
                            console.error('Fetch error:', error);
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                    }
                });

                // Non-JS fallback handling might be different now
                // form.addEventLiDB_CONNECTION=mysql
                DB_HOST=127.0.0.1
                DB_PORT=3306
                DB_DATABASE=your_database_name
                DB_USERNAME=your_username
                DB_PASSWORD=your_passwordstener('submit', function(e) { ... });
            });
        });
    </script>
</body>
</html>