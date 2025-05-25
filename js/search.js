const searchInput = document.querySelector(".searchbar");
const resultsBox = document.getElementById("results-box");


const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
};

const createSearchCard = (service) => {
    const card = document.createElement('div');
    card.className = 'search-card';
    
    const imageUrl = service.image ? `../${service.image}` : '../images/default_service.jpg';

    card.innerHTML = `
        <div class="card-image">
            <img src="${imageUrl}" alt="${service.title}">
        </div>
        <div class="card-content">
            <h3>${service.title}</h3>
            ${service.seller_name ? `<p class="seller">por ${service.seller_name}</p>` : ''}
            ${service.price ? `<p class="price">€${service.price.toFixed(2)}</p>` : ''}
        </div>
    `;
    
    card.onclick = () => {
        window.location.href = `service.php?id=${service.id}`;
    };
    
    return card;
};


const renderSearchResults = (results) => {
    resultsBox.innerHTML = '';
    
    if (!Array.isArray(results)) {
        throw new Error('Invalid results data');
    }

    if (results.length === 0) {
        resultsBox.innerHTML = '<div class="no-results">Nenhum resultado encontrado</div>';
        resultsBox.style.display = 'block';
        return;
    }

    const cardsContainer = document.createElement('div');
    cardsContainer.className = 'search-cards-container';

    results.forEach(service => {
        cardsContainer.appendChild(createSearchCard(service));
    });

    resultsBox.appendChild(cardsContainer);
    resultsBox.style.display = 'block';
};


const handleSearch = debounce(async () => {
    const query = searchInput.value.trim();
    
    if (query === "") {
        resultsBox.style.display = 'none';
        return;
    }

    try {
        const response = await fetch(`../controller/searchController.php?query=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        renderSearchResults(data);
    } catch (error) {
        console.error('Erro ao buscar os serviços:', error);
        resultsBox.innerHTML = '<div class="error">Erro ao carregar resultados</div>';
        resultsBox.style.display = 'block';
    }
}, 300);


searchInput.addEventListener("input", handleSearch);

document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-wrapper') && !e.target.closest('#results-box')) {
        resultsBox.style.display = 'none';
    }
});