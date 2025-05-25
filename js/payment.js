
const creditElement = document.getElementById("credit-form");
const mbWayElement = document.getElementById("mbWay-form");
const submitButton = document.getElementById("submitBtn");
const phoneInput = document.getElementById("phoneInput");
const fullNameInput = document.getElementById("fullName");
const termsCheckbox = document.getElementById("termsCheckbox");



window.addEventListener('DOMContentLoaded', function() {
        toggleForm('mb'); 
});

function validatePhoneNumber(phoneElement) {
    const phone = phoneElement.value;

    const regex = /^\+?[0-9]{9,15}$/;
    return regex.test(phone);
}


function isEmpty(inputElement) {
    return inputElement.value.trim() === '';
}


function toggleForm(method) {
    creditElement.style.display = (method === "card") ? "block" : "none";
    mbWayElement.style.display = (method === "mb") ? "block" : "none";
    
    phoneInput.style.border = '';
    fullNameInput.style.border = '';
}


submitButton.addEventListener('click', function(e) {
    let isValid = true;
    
    
    if (mbWayElement.style.display === "block") {
        
        if (!validatePhoneNumber(phoneInput)) {
            phoneInput.style.border = '2px solid red';
            isValid = false;
        }
        
        if (isEmpty(fullNameInput)) {
            fullNameInput.style.border = '2px solid red';
            isValid = false;
        }
    } else if (creditElement.style.display === "block") {

        if (isEmpty(document.getElementById("cardNumber"))) {
            document.getElementById("cardNumber").style.border = '2px solid red';
            isValid = false;
        }
        
        if (isEmpty(fullNameInput)) {
            fullNameInput.style.border = '2px solid red';
            isValid = false;
        }
    }
    
    if (!termsCheckbox.checked) {
        termsCheckbox.parentElement.style.color = 'red';
        isValid = false;
    } else {
        termsCheckbox.parentElement.style.color = '';
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('Please correct the highlighted fields before submitting.');
        return false;
    }
    
    return true;
});

phoneInput.addEventListener('input', function() {
    if (this.value && !validatePhoneNumber(this)) {
        this.style.border = '2px solid orange';
    } else {
        this.style.border = '';
    }
});

fullNameInput.addEventListener('input', function() {
    if (isEmpty(this)) {
        this.style.border = '2px solid orange';
    } else {
        this.style.border = '';
    }
});