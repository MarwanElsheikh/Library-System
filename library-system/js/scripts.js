// Example: Form Validation
function validateForm() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;

    if (!name || !email) {
        alert("All fields are required!");
        return false;
    }
    return true;
    
}
