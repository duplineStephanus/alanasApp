const logoutForm = document.getElementById('logout-form'); 
const logoutBtn = document.querySelectorAll('.logout-btn'); // class selector

logoutBtn.forEach(element => {
    element.addEventListener('click', () => {
        logoutForm.submit();
    });
});
