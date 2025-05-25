// Image gallery functionality
const thumbnails = document.querySelectorAll('.thumbnail');
const currentImage = document.getElementById('current-image');

thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
        // Update main image
        const imgSrc = this.querySelector('img').src;
        currentImage.src = imgSrc;

        // Update active state
        thumbnails.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

// Profile dropdown functionality
const profileDropdown = document.querySelector('.profile-dropdown');
if (profileDropdown) {
    profileDropdown.addEventListener('click', function(event) {
        this.querySelector('.dropdown-menu').classList.toggle('active');
        event.stopPropagation();
    });
}
document.addEventListener('click', function() {
    const activeDropdown = document.querySelector('.dropdown-menu.active');
    if (activeDropdown) {
        activeDropdown.classList.remove('active');
    }
});

const images=document.querySelectorAll('.carousel .image-container');
const nextButton = document.querySelector('.carousel .next');
const prevButton = document.querySelector('.carousel .prev');
let currentIndex = 0;
function showImage(index){
    images.forEach((image, i) => {
        if(i==index){
            image.classList.add( 'active' );
        }
        else{
            image.classList.remove( 'active' );
        }
 

    });
}

if(images.length>0){
    prevButton.addEventListener('click', () => {
        currentIndex=(currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    });
    nextButton.addEventListener('click', () => {
        currentIndex=(currentIndex + 1) % images.length;
        showImage(currentIndex);
    });
}

