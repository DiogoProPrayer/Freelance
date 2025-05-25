function clearErrorMessages() {
    document.querySelectorAll('.error-message').forEach(el => {
        el.textContent = '';
    });
}

function openAuthPopup(form) {
    document.getElementById('Popup').classList.add('active');
    showAuthForm(form);
    clearErrorMessages();
}

function closeAuthPopup() {
    document.getElementById('Popup').classList.remove('active');
}

function showAuthForm(form) {
    clearErrorMessages();
    document.getElementById('loginForm').classList.remove('active');
    document.getElementById('registerForm').classList.remove('active');
    document.getElementById(form + 'Form').classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('Popup');
    if (popup) {
        popup.addEventListener('click', e => {
            if (e.target.id === 'close') closeAuthPopup();
        });
    }

    // CORREÇÃO PRINCIPAL: Selecionar o formulário correto
    const loginForm = document.querySelector('#loginForm form');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            clearErrorMessages();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../controller/loginController.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json' // Garante que recebemos JSON
                    }
                });
                
                // Adicione este log para depuração
                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    window.location.href = '/../pages/homepage.php';
                } else {
                    // CORREÇÃO: Usar os IDs exatos dos elementos de erro
                    if (data.errors.username) {
                        document.getElementById('username-error').textContent = data.errors.username;
                    }
                    if (data.errors.password) {
                        document.getElementById('password-error').textContent = data.errors.password;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('username-error').textContent = 'An error occurred. Please try again.';
            }
        });
    }
});

window.showAuthForm = showAuthForm;