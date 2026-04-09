function selectItem(element, event) {
    // 1. Evita que la página salte al principio al hacer clic en #
    if (event) event.preventDefault();

    // 2. Busca el contenedor .menu más cercano (Nav o Aside)
    const parentMenu = element.closest('.menu');

    if (parentMenu) {
        // 3. Quita 'active' solo de los botones de ESTE menú
        const items = parentMenu.querySelectorAll('.menu-item');
        items.forEach(item => {
            item.classList.remove('active');
        });

        // 4. Se lo pone al elemento clicado
        element.classList.add('active');
    }
}
