document.addEventListener("DOMContentLoaded", function () {
    // Handle Job Posting
    document.getElementById("post-job-form").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("post_job.php", {
            method: "POST",
            body: formData
        }).then(response => response.text()).then(data => {
            alert(data);
            location.reload();
        });
    });

    // Handle Job Deletion
    document.querySelectorAll(".delete-job").forEach(button => {
        button.addEventListener("click", function () {
            let jobId = this.getAttribute("data-id");

            fetch("delete_job.php", {
                method: "POST",
                body: JSON.stringify({ job_id: jobId }),
                headers: { "Content-Type": "application/json" }
            }).then(response => response.text()).then(data => {
                alert(data);
                location.reload();
            });
        });
    });

    // Fetch Applicants
    function loadApplicants() {
        fetch("get_applicants.php")
            .then(response => response.text())
            .then(data => document.getElementById("applicants-list").innerHTML = data);
    }
    loadApplicants();
});
