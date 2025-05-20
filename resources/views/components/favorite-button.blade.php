<div class="favorite-button" data-recipe-id="{{ $recipe->id }}">
    <button type="button" class="btn btn-sm {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }}" 
            onclick="toggleFavorite({{ $recipe->id }})">
        <i class="bi {{ $isFavorited ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        <span class="favorite-count ms-1">{{ $favoritesCount }}</span>
    </button>
</div>

@push('scripts')
<script>
function toggleFavorite(recipeId) {
    if (!{{ Auth::check() ? 'true' : 'false' }}) {
        window.location.href = '{{ route('login') }}';
        return;
    }

    const button = document.querySelector(`.favorite-button[data-recipe-id="${recipeId}"] button`);
    const icon = button.querySelector('i');
    const countSpan = button.querySelector('.favorite-count');
    
    // Show loading state
    const originalHTML = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    
    // Call the web route
    fetch(`/favorites/${recipeId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ _token: '{{ csrf_token() }}' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button appearance
            if (data.is_favorited) {
                button.classList.remove('btn-outline-danger');
                button.classList.add('btn-danger');
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
            } else {
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-danger');
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
            }
            
            // Update count
            if (countSpan && typeof data.favorites_count !== 'undefined') {
                countSpan.textContent = data.favorites_count;
            }
            
            // Dispatch event to update favorites count in navigation
            document.dispatchEvent(new Event('favoritesUpdated'));
        } else {
            console.error('Error toggling favorite:', data.message || 'Unknown error');
            alert('Failed to update favorite: ' + (data.message || 'Please try again later'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
    })
    .finally(() => {
        // Reset button state
        button.disabled = false;
        button.innerHTML = originalHTML;
    });
}

// Initialize favorite buttons on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check favorite status for each button
    document.querySelectorAll('.favorite-button').forEach(button => {
        const recipeId = button.dataset.recipeId;
        
        fetch(`/favorites/${recipeId}/check`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const btn = button.querySelector('button');
                const icon = button.querySelector('i');
                const countSpan = button.querySelector('.favorite-count');
                
                if (data.is_favorited) {
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-danger');
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                } else {
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                }
                
                if (countSpan && typeof data.favorites_count !== 'undefined') {
                    countSpan.textContent = data.favorites_count;
                }
            } else {
                console.error('Error checking favorite status:', data.message || 'Unknown error');
            }
        })
        .catch(error => console.error('Error checking favorite status:', error));
    });
});
</script>
@endpush
