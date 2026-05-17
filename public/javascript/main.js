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
                    searchResults.innerHTML = '<p style="padding:12px 16px;color:#999;">Sin resultados</p>';
                } else {
                    searchResults.innerHTML = users.map(user => `
                        <a href="/profile/${user.id}" class="nav-search-result-item">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.username)}" alt="${user.username}">
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
