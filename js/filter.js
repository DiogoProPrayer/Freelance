function handleClickedCategory(categoryName, isLoggedIn) {
    if (isLoggedIn) {
        const formData = new FormData();
        formData.append('category', categoryName);
        fetch('../controller/filterController.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            try {
                const jsonData = JSON.parse(data);
                console.log("Resposta JSON:", jsonData); // Para depuração

                if (jsonData.success) {
                    window.location.href = `../pages/filter.php?category=${encodeURIComponent(categoryName)}`;
                } else {
                    console.error('Erro no servidor:', jsonData.message);
                }
            } catch (error) {
                console.error('Erro ao processar JSON:', error);
                console.log('Resposta recebida:', data);
            }
        })
        .catch(error => console.error('Erro na requisição AJAX:', error));
    } else {
        openAuthPopup('login');
    }
}

window.handleClickedCategory = handleClickedCategory;
