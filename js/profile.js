
// Toggle edit profile form
const editProfileBtn = document.getElementById('editProfileBtn');
const editProfileForm = document.getElementById('editProfileForm');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const imageUploadBox = document.getElementById('imageUploadBox');
const profileImageInput = document.getElementById('profile_image_input');
const imagePreview = document.getElementById('imagePreview');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const currentProfilePicDisplay = document.getElementById('currentProfilePicDisplay'); 
const topBarAvatar = document.querySelector(' .profile-icon img');
src=topBarAvatar.src;

if (editProfileBtn) {
    editProfileBtn.addEventListener('click', function() {
        editProfileForm.classList.remove('hidden');
    });
}

if (cancelEditBtn) {
    cancelEditBtn.addEventListener('click', function() {
        editProfileForm.classList.add('hidden');
        // Optional ly reset image preview on cancel
        imagePreview.classList.add('hidden');
        uploadPlaceholder.classList.remove('hidden');
        imagePreview.src = '';
        profileImageInput.value = ''; 
        if (currentProfilePicDisplay) {
            currentProfilePicDisplay.src = src; // Reset to original image
        }
        if (topBarAvatar) {
            topBarAvatar.src=src;
        }
    });
}



// Status toggle functionality
const statusToggleCheckbox = document.getElementById('statusToggleCheckbox');
const toggleStatusValueInput = document.getElementById('toggle_status_value');
const statusToggleForm = document.getElementById('statusToggleForm');
const toggleSubmitBtn = document.getElementById('toggleSubmitBtn');

console.log('Toggle Submit Button:', toggleSubmitBtn);

if (statusToggleCheckbox) {
    statusToggleCheckbox.addEventListener('change', function() {
        toggleStatusValueInput.value = this.checked ? 'seller' : 'client';
        
        // statusToggleForm.submit(); // Or click a hidden submit button
        toggleSubmitBtn.click(); // Programmatically click the hidden submit button
    });
}


// --- Improved Image Upload Script ---


if (imageUploadBox) {
    // Trigger file input click when the box is clicked
    imageUploadBox.addEventListener('click', () => {
        profileImageInput.click();
    });

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageUploadBox.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false); // Prevent browser opening file
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone when item is dragged over
    ['dragenter', 'dragover'].forEach(eventName => {
        imageUploadBox.addEventListener(eventName, () => {
            imageUploadBox.classList.add('drag-over');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        imageUploadBox.addEventListener(eventName, () => {
            imageUploadBox.classList.remove('drag-over');
        }, false);
    });

    // Handle dropped files
    imageUploadBox.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length) {
            profileImageInput.files = files; // Assign to the input for form submission
            handleFiles(files);
        }
    }

    // Handle file selection from click
    profileImageInput.addEventListener('change', function(event) {
        if (event.target.files.length) {
            handleFiles(event.target.files);
        }
    });
    
    function handleFiles(files) {
        const file = files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadPlaceholder.classList.add('hidden');

                // Also update the main profile picture display in real-time
                if (currentProfilePicDisplay) {
                    currentProfilePicDisplay.src = e.target.result;
                }

                // Update the avatar in the top navigation bar
            
             
                if (topBarAvatar) {
                    topBarAvatar.src = e.target.result;
                }
            }
            reader.readAsDataURL(file);
        } else {
            // Handle non-image file or clear preview if needed
            alert("Please select an image file (jpeg, png, gif).");
            imagePreview.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
            topBarAvatar.src = src; 
            currentProfilePicDisplay.src =src; 
            imagePreview.src = '';
            profileImageInput.value = '';
            
        }
    }

}
document.addEventListener('DOMContentLoaded', function () {
    const notifications = document.querySelectorAll('.alert');
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => {
                notification.remove(); 
            }, 1000);
        }, 1000); 
    });
});
