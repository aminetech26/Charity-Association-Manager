const ROOT = 'http://localhost/TDWProject/';

document.addEventListener('DOMContentLoaded', function() {

    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function() {
            userDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    } else {
        console.error('User menu button or dropdown element not found');
    }

    const drawerButton = document.querySelector('[data-drawer-target="logo-sidebar"]');
    const drawer = document.getElementById('logo-sidebar');

    if (drawerButton && drawer) {
        drawerButton.addEventListener('click', function() {
            drawer.classList.toggle('-translate-x-full');
        });
    } else {
        console.error('Drawer button or drawer element not found');
    }
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');
            if (target) {
                loadContent(target);
            } else {
                console.error('No data-target attribute found on menu item');
            }
        });
    });

    function loadContent(target) {
        const mainContent = document.querySelector('main');
        if (!mainContent) return;
    
        mainContent.innerHTML = '';
    
        document.querySelectorAll('script[data-dynamic-script]').forEach(oldScript => {
            oldScript.remove();
            console.log('Removed old script:', oldScript.src);
        });
    
        fetch(`${ROOT}admin/Admin/${target}`)
            .then(response => response.ok ? response.text() : Promise.reject('Response not ok'))
            .then(data => {
                mainContent.innerHTML = data;
    
                const scripts = mainContent.querySelectorAll('script');
                scripts.forEach(script => {
                
    
                    const newScript = document.createElement('script');
                    if (script.src) {
                        newScript.src = script.src;
                    } else {
                        newScript.textContent = script.textContent;
                    }
                    newScript.setAttribute('data-dynamic-script', 'true'); 
                    document.body.appendChild(newScript);
                    console.log('Script appended to body:', newScript);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                mainContent.innerHTML = 'Failed to load content.';
            });
    }

});