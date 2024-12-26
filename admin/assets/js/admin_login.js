const ROOT = 'http://localhost/TDWProject/';

document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const errorAlert = document.getElementById('error-alert');
    const errorMessage = errorAlert.querySelector('div');
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'error') {
            errorMessage.textContent = data.message;
            errorAlert.classList.remove('hidden');
        } else if (data.status === 'success') {
            window.location.href = ROOT + 'admin/Admin/dashboard';
        }
    } catch (err) {
        errorMessage.textContent = 'Une erreur est survenue. Veuillez réessayer.';
        errorAlert.classList.remove('hidden');
    }
});