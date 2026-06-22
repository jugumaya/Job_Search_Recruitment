document.addEventListener("DOMContentLoaded", function () {
    // Apply button click event
    document.querySelectorAll(".apply-btn").forEach(button => {
        button.addEventListener("click", function () {
            let jobId = this.getAttribute("data-job-id");

            fetch("apply_backend.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `job_id=${jobId}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Show success or error message
                if (data.success) {
                    this.disabled = true; // Disable button after applying
                    this.innerText = "Applied";
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });

    // Live search functionality
    document.querySelector("input[name='search']").addEventListener("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.querySelector("form").submit();
        }
    });
});
