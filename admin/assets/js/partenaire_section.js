{
    const tabs = document.querySelectorAll('.tab-link');
    const tabPanes = document.querySelectorAll('.tab-pane');


    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            tabs.forEach(t => t.classList.remove('active', 'text-blue-600', 'border-blue-600'));
            tabPanes.forEach(pane => pane.classList.add('hidden'));

            this.classList.add('active', 'text-blue-600', 'border-blue-600');
            const target = this.getAttribute('href');
            document.querySelector(target).classList.remove('hidden');
        });
    });

    if (tabs.length > 0) {
        tabs[0].classList.add('text-blue-600', 'border-blue-600');
        document.querySelector(tabs[0].getAttribute('href')).classList.remove('hidden');
    }
}