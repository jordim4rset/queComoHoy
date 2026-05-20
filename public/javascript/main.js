function selectItem(element, event) {
    const parentMenu = element.closest('.menu');

    if (parentMenu) {
        const items = parentMenu.querySelectorAll('.menu-item');
        items.forEach(item => {
            item.classList.remove('active');
        });

        element.classList.add('active');
    }
}

function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('open');
}

/** scroll de la animacion */
const posts = document.querySelectorAll('.post');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });

posts.forEach(post => observer.observe(post));

/**Conteo de megustas  */

document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const recipeId = this.dataset.recipeId;
        const countEl = this.nextElementSibling;

        fetch(`/recipes/${recipeId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            countEl.textContent = data.count;
            this.style.color = data.liked ? 'red' : 'currentColor';
        });
    });
});

// Toggle comments section in the feed
document.querySelectorAll('.comment-toggle').forEach(btn => {
    btn.addEventListener('click', function () {
        const recipeId = this.dataset.recipeId;
        const commentsBox = document.querySelector(`.comments-box-${recipeId}`);

        if (!commentsBox) return;
        commentsBox.style.display = commentsBox.style.display === 'none' ? 'block' : 'none';
    });
});

// Delegated delete handler for comments
document.addEventListener('click', async (event) => {
    const deleteBtn = event.target.closest('.delete-comment-btn');
    if (!deleteBtn) return;

    event.preventDefault();

    if (!confirm(document.body.dataset.deleteCommentConfirm || '¿Eliminar este comentario?')) return;

    const commentId = deleteBtn.dataset.commentId;

    try {
        const response = await fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            }
        });

        const data = await response.json();

        if (data.success) {
            const commentEl = document.querySelector(`.comment[data-comment-id="${commentId}"]`);
            if (commentEl) {
                commentEl.remove();
            }

            const recipeId = deleteBtn.dataset.recipeId;
            const commentCount = recipeId ? document.querySelector(`.comments-count-${recipeId}`) : null;
            if (commentCount) {
                commentCount.textContent = Math.max(0, parseInt(commentCount.textContent) - 1);
            }
        }
    } catch (error) {
        console.error(document.body.dataset.deleteCommentError || 'Error al eliminar comentario:', error);
        alert(document.body.dataset.deleteCommentError || 'Error al eliminar el comentario');
    }
});

/**Este js es para el buscador del nav */

const searchInput = document.getElementById('nav-search-input');
const searchResults = document.getElementById('nav-search-results');

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length < 1) {
            searchResults.classList.remove('active');
            searchResults.innerHTML = '';
            return;
        }

        fetch(`/users/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                if (users.length === 0) {
                    const noResultsText = document.body.dataset.noResultsText || 'Sin resultados';
                    searchResults.innerHTML = `<p style="padding:12px 16px;color:#999;">${noResultsText}</p>`;
                } else {
                    searchResults.innerHTML = users.map(user => `
                        <a href="/profile/${user.id}" class="nav-search-result-item">
                            <img src="${user.profile_photo_url}" alt="${user.username}">
                            <span>${user.username}</span>
                        </a>
                    `).join('');
                }
                searchResults.classList.add('active');
            });
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('active');
        }
    });
}
