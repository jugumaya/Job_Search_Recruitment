document.addEventListener("DOMContentLoaded", function () {
    // Select all accept and reject buttons
    const acceptButtons = document.querySelectorAll(".accept-btn");
    const rejectButtons = document.querySelectorAll(".reject-btn");

    // Function to update status in database
    function updateStatus(applicationId, status) {
        fetch("update-application-status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${applicationId}&status=${status}`
        })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                location.reload(); // Refresh the page after status update
            } else {
                alert("Error updating status.");
            }
        });
    }

    // Add event listeners for accept buttons
    acceptButtons.forEach(button => {
        button.addEventListener("click", function () {
            const applicationId = this.dataset.id;
            if (confirm("Are you sure you want to accept this applicant?")) {
                updateStatus(applicationId, "accepted");
            }
        });
    });

    // Add event listeners for reject buttons
    rejectButtons.forEach(button => {
        button.addEventListener("click", function () {
            const applicationId = this.dataset.id;
            if (confirm("Are you sure you want to reject this applicant?")) {
                updateStatus(applicationId, "rejected");
            }
        });
    });
});
