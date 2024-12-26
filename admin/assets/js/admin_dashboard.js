const ROOT = 'http://localhost/TDWProject/';

document.addEventListener('DOMContentLoaded', function() {
    const drawerButton = document.querySelector('[data-drawer-target="logo-sidebar"]');
    const drawer = document.getElementById('logo-sidebar');

    drawerButton.addEventListener('click', function() {
        drawer.classList.toggle('-translate-x-full');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');
            loadContent(target);
        });
    });

    function loadContent(target) {
        const mainContent = document.querySelector('main');
        mainContent.innerHTML = ''

        fetch(`${ROOT}admin/Admin/${target}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        console.log('Response data:', data); // Log the response
        mainContent.innerHTML = data;
    })
    .catch(error => {
        console.error('Error loading content:', error);
    });
    }
});