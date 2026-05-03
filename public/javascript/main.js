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
