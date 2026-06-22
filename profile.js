document.addEventListener("DOMContentLoaded", function () {
    // Open and close modals
    const editModal = document.getElementById("edit-profile-modal");
    const resetModal = document.getElementById("reset-password-modal");

    document.getElementById("edit-profile-btn").addEventListener("click", () => editModal.style.display = "block");
    document.getElementById("reset-password-btn").addEventListener("click", () => resetModal.style.display = "block");

    document.querySelectorAll(".close").forEach(closeBtn => {
        closeBtn.addEventListener("click", () => {
            editModal.style.display = "none";
            resetModal.style.display = "none";
        });
    });

    // Handle profile update
    document.getElementById("edit-profile-form").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("update_profile.php", {
            method: "POST",
            body: formData
        }).then(response => response.text()).then(data => {
            alert(data);
            location.reload();
        });
    });

    // Handle password reset
    document.getElementById("reset-password-form").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("reset_password.php", {
            method: "POST",
            body: formData
        }).then(response => response.text()).then(data => {
            alert(data);
            resetModal.style.display = "none";
        });
    });
});
