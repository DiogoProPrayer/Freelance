function cleanErrorMessages() {
    document.querySelectorAll('.error-message').forEach(element => {
        element.textContent = '';
    });
}

const filterToggle = document.getElementById("filterToggle");
const filterForm = document.getElementById("filterForm");

filterToggle.addEventListener("click", () => {
    filterForm.classList.toggle("active");
});



filterForm.addEventListener("submit", function(event) {
    event.preventDefault();


    const form = event.target;

    cleanErrorMessages();

    let max = form.max.value.trim();
    let minRating = form.rat.value.trim();
    let minOrders = form.ord.value.trim();

    let hasError = false;

    const sortValue = form.sort.value;
    const orderValue = form.order.value;
    
    if ((sortValue === 'none' && orderValue !== 'none') || 
        (sortValue !== 'none' && orderValue === 'none')) {
        
        if (sortValue === 'none') {
            form.sort.nextElementSibling.textContent = 'Please select a filter';
        }
        if (orderValue === 'none') {
            form.order.nextElementSibling.textContent = 'Please select an order';
        }
        hasError = true;
    }



    if (form.max.nextElementSibling) {
        form.max.nextElementSibling.textContent = '';
        
    }
    if (form.rat.nextElementSibling) {
        form.rat.nextElementSibling.textContent = '';
    }
    if (form.ord.nextElementSibling) {
        form.ord.nextElementSibling.textContent = '';
    }

    if (max) {
        if (Number(max) < 0) {
            form.max.nextElementSibling.textContent = 'Maximal Price cannot be smaller than 0';
            hasError = true;
        }
    }

    if (minRating) {
        const rating = Number(minRating);
        if (rating < 1 || rating > 5) {
            form.rat.nextElementSibling.textContent = 'Rating needs to be between 1 and 5';
            hasError = true;
        }
    }

    if (minOrders) {
        if (Number(minOrders) < 0) {
            form.ord.nextElementSibling.textContent = 'Minimal orders cannot be negative';
            hasError = true;
        }
    }

    if (!hasError) {
        filterForm.classList.remove("active");
        
        const parameters = new URLSearchParams(new FormData(form)).toString();

        fetch('../pages/filter.php?' + parameters, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => document.querySelector('.profile-content').innerHTML = html)
        .catch(error => {
            console.error("Error loading Filters", error);
        });
    }
});

