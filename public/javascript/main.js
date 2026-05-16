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
