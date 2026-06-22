document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const editModal = document.getElementById("edit-profile-modal");
    const resetModal = document.getElementById("reset-password-modal");

    // Open modals
    document.getElementById("edit-profile-btn").addEventListener("click", () => {
        editModal.style.display = "block";
    });

    document.getElementById("reset-password-btn").addEventListener("click", () => {
        resetModal.style.display = "block";
    });

    // Close modals
    document.querySelectorAll(".close").forEach(closeBtn => {
        closeBtn.addEventListener("click", function () {
            editModal.style.display = "none";
            resetModal.style.display = "none";
        });
    });

    // Close modal when clicking outside
    window.addEventListener("click", function (event) {
        if (event.target === editModal) {
            editModal.style.display = "none";
        }
        if (event.target === resetModal) {
            resetModal.style.display = "none";
        }
    });

    // Handle profile update
    document.getElementById("edit-profile-form").addEventListener("submit", function (e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        let formData = new FormData(this);

        fetch("update_profile.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes("success") || data.includes("Success")) {
                    location.reload();
                } else {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    });

    // Handle password reset
    document.getElementById("reset-password-form").addEventListener("submit", function (e) {
        e.preventDefault();

        const newPassword = this.querySelector('input[name="new_password"]').value;
        const confirmPassword = this.querySelector('input[name="confirm_password"]').value;

        // Validate passwords match
        if (newPassword !== confirmPassword) {
            alert("New password and confirmation password do not match!");
            return;
        }

        // Validate password length
        if (newPassword.length < 6) {
            alert("Password must be at least 6 characters long!");
            return;
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Resetting...';
        submitBtn.disabled = true;

        let formData = new FormData(this);

        fetch("reset_password.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes("success") || data.includes("Success")) {
                    resetModal.style.display = "none";
                    this.reset();
                }
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    });
});