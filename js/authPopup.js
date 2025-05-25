function openAuthPopup(form) {
    document.getElementById('Popup').classList.add('active');
    showAuthForm(form);
}

function closeAuthPopup() {
    document.getElementById('Popup').classList.remove('active');
}

function showAuthForm(form) {
    document.getElementById('loginForm').classList.remove('active');
    document.getElementById('registerForm').classList.remove('active');
    document.getElementById(form + 'Form').classList.add('active');
}

window.showAuthForm = showAuthForm;

document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('Popup');
    if (popup) {
        popup.addEventListener('click', e => {
            if (e.target.id === 'close') closeAuthPopup();
        });
    }
});
