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

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('[name="_token"]')?.value
        || '';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

const postObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });

function observePosts(root = document) {
    root.querySelectorAll('.post').forEach(post => postObserver.observe(post));
}

observePosts();

document.addEventListener('click', async (event) => {
    const likeBtn = event.target.closest('.like-btn');

    if (!likeBtn) {
        return;
    }

    const recipeId = likeBtn.dataset.recipeId;
    const countEl = likeBtn.nextElementSibling;

    try {
        const response = await fetch(`/recipes/${recipeId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        countEl.textContent = data.count;
        likeBtn.style.color = data.liked ? 'red' : 'currentColor';
    } catch (error) {
        console.error('Error al actualizar like:', error);
    }
});

document.addEventListener('click', (event) => {
    const commentToggle = event.target.closest('.comment-toggle');

    if (!commentToggle) {
        return;
    }

    const recipeId = commentToggle.dataset.recipeId;
    const commentsBox = document.querySelector(`.comments-box-${recipeId}`);

    if (!commentsBox) {
        return;
    }

    commentsBox.style.display = commentsBox.style.display === 'none' ? 'block' : 'none';
});

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('.comment-form');

    if (!form) {
        return;
    }

    event.preventDefault();

    const recipeId = form.dataset.recipeId;
    const textarea = form.querySelector('textarea');
    const content = textarea.value.trim();

    if (!content) {
        return;
    }

    try {
        const response = await fetch(form.dataset.commentUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ content })
        });

        const data = await response.json();

        if (!data.success) {
            return;
        }

        const commentsContainer = document.querySelector(`.comments-list-${recipeId}`);
        const emptyPlaceholder = commentsContainer.querySelector('.no-comments-placeholder');

        if (emptyPlaceholder) {
            emptyPlaceholder.remove();
        }

        const newComment = `
            <div class="comment" data-comment-id="${data.comment.id}" style="display: flex; gap: 10px; margin-bottom: 15px;">
                <img
                    src="${data.comment.avatar}"
                    alt="${data.comment.username}"
                    class="avatar"
                    style="width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;">

                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong>${data.comment.username}</strong>
                        <span style="font-size: 12px; color: #999;">${data.comment.created_at}</span>
                    </div>
                    <p style="margin: 5px 0 0 0; color: #333;">${escapeHtml(data.comment.content)}</p>
                    <button class="delete-comment-btn" data-comment-id="${data.comment.id}" data-recipe-id="${recipeId}" style="margin-top: 8px; background: none; border: none; color: #999; cursor: pointer; font-size: 12px;">Eliminar</button>
                </div>
            </div>
        `;

        commentsContainer.insertAdjacentHTML('afterbegin', newComment);
        textarea.value = '';

        const commentCount = document.querySelector(`.comments-count-${recipeId}`);
        if (commentCount) {
            commentCount.textContent = parseInt(commentCount.textContent) + 1;
        }
    } catch (error) {
        console.error('Error al añadir comentario:', error);
        alert('Error al añadir comentario');
    }
});

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
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
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

function initInfiniteScroll() {
    const containers = document.querySelectorAll('[data-infinite-scroll-container]');

    if (!containers.length) {
        return;
    }

    const loadObserver = new IntersectionObserver((entries) => {
        entries.forEach(async (entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            const trigger = entry.target;
            const nextPageUrl = trigger.dataset.nextPageUrl;

            if (!nextPageUrl || trigger.dataset.loading === 'true') {
                return;
            }

            trigger.dataset.loading = 'true';

            try {
                const response = await fetch(nextPageUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                const container = trigger.closest('[data-infinite-scroll-container]');
                const template = document.createElement('template');

                template.innerHTML = data.html.trim();

                const appendedNodes = Array.from(template.content.children);
                trigger.before(template.content);

                appendedNodes.forEach(node => {
                    if (node.matches?.('.post')) {
                        postObserver.observe(node);
                    }
                    observePosts(node);
                });

                trigger.dataset.nextPageUrl = data.next_page_url || '';

                if (!data.next_page_url) {
                    loadObserver.unobserve(trigger);
                    trigger.remove();
                } else {
                    trigger.dataset.loading = 'false';
                }
            } catch (error) {
                console.error('Error al cargar más elementos:', error);
                trigger.dataset.loading = 'false';
            }
        });
    }, {
        rootMargin: '400px 0px',
    });

    containers.forEach(container => {
        const trigger = container.querySelector('[data-infinite-scroll-trigger]');

        if (trigger?.dataset.nextPageUrl) {
            loadObserver.observe(trigger);
        } else if (trigger) {
            trigger.remove();
        }
    });
}

initInfiniteScroll();

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
