const imageContainer = document.querySelectorAll('.contains_service_image')


if (imageContainer.length === 0) {
    console.error('No element with class "contains_service_image" found.');
} else {
    imageContainer.forEach(imageContainer => {
        const imageId = imageContainer.getAttribute('data-image-id');
        const button = imageContainer.querySelector('.deleteImage');
        imageContainer.addEventListener('click', function (e) {
            if (e.target == button) {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();
                    fetch('/controller/serviceDetailsController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete&imageId=${encodeURIComponent(imageId)}`,
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                imageContainer.remove();
                            } else {
                                console.error('Error deleting image');
                            }
                        })
                });
                return; 
            }
            if (button.classList.contains('hidden')) {
                button.classList.remove('hidden');
            } else {
                button.classList.add('hidden');
            }


        });
    });
   




}

