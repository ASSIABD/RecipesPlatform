@extends('layouts.main')

@section('corps')
<div class="container py-4">

    {{-- Stylized page title --}}
    <h1 class="h2 fw-bold text-danger mb-4 pb-2 border-bottom border-danger border-opacity-25">
        {{-- Or border-danger-subtle if Bootstrap 5.3+ --}}
        Author Profiles
    </h1>

    @if($users->isNotEmpty())
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            {{-- Adjustment: row-cols-sm-2 and row-cols-md-3 for better responsiveness on tablets --}}
            @foreach ($users as $user)
                <div class="col">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body d-flex flex-column pt-4"> {{-- pt-4 for a bit more space at the top --}}
                            {{-- Avatar or Placeholder --}}
                            <div class="mx-auto mb-3">
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('avatars/avatarInconnue.jpg') }}" 
                                 alt="{{ $user->name }}'s avatar" 
                                 class="rounded-circle img-fluid" 
                                 style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #dee2e6;"
                                 onerror="this.onerror=null; this.src='{{ asset('avatars/avatarInconnue.jpg') }}'">
                            </div>

                            {{-- User name --}}
                            <h5 class="card-title fw-bold mb-1 fs-6"> {{-- fs-6 for a slightly smaller font size for the card title --}}
                                <a href="{{ route('auteurs.recipes', $user->id) }}" class="text-dark text-decoration-none stretched-link">
                                    {{ $user->name }}
                                </a>
                            </h5>

                            {{-- Number of recipes (if available from the controller with withCount) --}}
                            @if(isset($user->recipes_count))
                                <p class="small text-muted mb-2">{{ $user->recipes_count }} recipe(s)</p>
                            @endif

                            {{-- User bio --}}
                            <p class="card-text small text-muted mb-auto">
                                {{ Str::limit($user->bio ?: 'No biography available.', 70) }} {{-- Shorter limit for cards and default message --}}
                            </p>
                        </div>
                        <div class="card-footer bg-light border-0">
                            <small class="text-muted">Member since: {{ $user->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination (if enabled in the controller) --}}
        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif

    @else
        <div class="alert alert-secondary text-center" role="alert">
            No user profiles to display at the moment.
        </div>
    @endif
</div>
@endsection
