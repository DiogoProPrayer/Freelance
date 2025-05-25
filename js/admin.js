// User Management
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        showConfirmModal(
            'Are you sure you want to delete this user? This action cannot be undone.',
            () => deleteUser(userId)
        );
    });
});

document.querySelectorAll('.promote-btn').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        showConfirmModal(
            'Are you sure you want to promote this user to Admin? They will have full access to the admin panel.',
            () => promoteUser(userId)
        );
    });
});

// Service Management
document.querySelectorAll('.delete-service-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default action
        e.stopPropagation(); // Stop the event from bubbling up
        const serviceId = this.getAttribute('data-service-id');
        showConfirmModal(
            'Are you sure you want to delete this service? This action cannot be undone.',
            () => deleteService(serviceId)
        );
    });
});

// Review Management
document.querySelectorAll('.delete-review-btn').forEach(button => {
    button.addEventListener('click', function() {
        const reviewId = this.getAttribute('data-review-id');
        showConfirmModal(
            'Are you sure you want to delete this review text? The rating will remain.',
            () => deleteReview(reviewId)
        );
    });
});

// Confirmation Modal
const modal = document.getElementById('confirmModal');
const confirmMessage = document.getElementById('confirmMessage');
const confirmButton = document.getElementById('confirmButton');
const cancelButton = document.getElementById('cancelButton');

function showConfirmModal(message, confirmCallback) {
    confirmMessage.textContent = message;
    modal.style.display = 'flex';

    confirmButton.onclick = () => {
        confirmCallback();
        modal.style.display = 'none';
    };

    cancelButton.onclick = () => {
        modal.style.display = 'none';
    };
}

// API Actions
function deleteUser(userId) {
    fetch('../controller/adminAjaxController.php', {
        method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_user&userId=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`tr[data-user-id="${userId}"]`).remove();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function promoteUser(userId) {
    fetch('../controller/adminAjaxController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=promote_user&userId=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`tr[data-user-id="${userId}"]`);
            row.querySelector('td:nth-child(7)').innerHTML = '<span class="admin-badge">Admin</span>';
            row.querySelector('.promote-btn').remove();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function deleteService(serviceId) {
    fetch('../controller/adminAjaxController.php', {
        method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_service&serviceId=${serviceId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`.service-list-item[data-service-id="${serviceId}"]`).remove();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function deleteReview(reviewId) {
    fetch('../controller/adminAjaxController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_review&reviewId=${reviewId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const reviewCard = document.querySelector(`.review-card[data-review-id="${reviewId}"]`);
            reviewCard.querySelector('.review-content').innerHTML = '<p class="no-review">No written review provided.</p>';
            reviewCard.querySelector('.delete-review-btn').remove();
        } else {
            alert('Error: ' + data.message);
        }
    });
}