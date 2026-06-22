function toggleFields() {
    var userType = document.getElementById("user_type").value;
    document.getElementById("seeker_fields").style.display = userType == "job_seeker" ? "block" : "none";
    document.getElementById("giver_fields").style.display = userType == "job_giver" ? "block" : "none";
}

function validateForm() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    if (name.trim() == "" || email.trim() == "" || password.trim() == "") {
        alert("Please fill out all required fields.");
        return false;
    }
    return true;
}
