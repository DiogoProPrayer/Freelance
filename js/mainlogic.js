
function handleClickedCategory(categoryName, isLoggedIn) {
    if (isLoggedIn) {
        window.location.href = `../pages/filter.php?category=${encodeURIComponent(categoryName)}`;
    } else {
        openAuthPopup('login');
    }
}

window.handleClickedCategory = handleClickedCategory;