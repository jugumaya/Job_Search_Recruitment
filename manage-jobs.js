document.addEventListener("DOMContentLoaded", function () {
    // Select all edit and delete buttons
    const editButtons = document.querySelectorAll(".edit-btn");
    const deleteButtons = document.querySelectorAll(".delete-btn");

    // Function to delete a job
    function deleteJob(jobId) {
        if (confirm("Are you sure you want to delete this job?")) {
            fetch("delete-job.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${jobId}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    location.reload(); // Refresh the page after deletion
                } else {
                    alert("Error deleting job.");
                }
            });
        }
    }

    // Function to edit a job
    function editJob(jobId) {
        window.location.href = `edit-job.php?id=${jobId}`;
    }

    // Add event listeners for edit buttons
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const jobId = this.dataset.id;
            editJob(jobId);
        });
    });

    // Add event listeners for delete buttons
    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const jobId = this.dataset.id;
            deleteJob(jobId);
        });
    });
});
