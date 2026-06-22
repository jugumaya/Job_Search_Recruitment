function validateLoginForm() {
    var email = document.getElementById("login_email").value;
    var password = document.getElementById("login_password").value;

    if (email.trim() == "" || password.trim() == "") {
        alert("Please enter both email and password.");
        return false;
    }
    return true;
}
