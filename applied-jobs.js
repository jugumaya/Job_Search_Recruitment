document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".withdraw-btn").forEach(button => {
        button.addEventListener("click", function () {
            let jobId = this.dataset.jobId;

            if (confirm("Are you sure you want to withdraw your application?")) {
                fetch("withdraw_application.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `job_id=${jobId}`
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
