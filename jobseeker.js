document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("job-search");
    const searchBtn = document.getElementById("search-btn");
    const jobListings = document.getElementById("job-listings");

    searchBtn.addEventListener("click", function () {
        let searchTerm = searchInput.value.toLowerCase();
        filterJobs(searchTerm);
    });

    searchInput.addEventListener("keyup", function () {
        let searchTerm = this.value.toLowerCase();
        filterJobs(searchTerm);
    });

    function filterJobs(searchTerm) {
        let filteredJobs = jobData.filter(job =>
            job.title.toLowerCase().includes(searchTerm) ||
            job.company.toLowerCase().includes(searchTerm)
        );

        displayJobs(filteredJobs);
    }

    function displayJobs(jobs) {
        jobListings.innerHTML = "";
        if (jobs.length === 0) {
            jobListings.innerHTML = "<p>No jobs found.</p>";
            return;
        }
        jobs.forEach(job => {
            let jobCard = document.createElement("div");
            jobCard.classList.add("job-card");
            jobCard.innerHTML = `
                <h3>${job.title}</h3>
                <p>Company: ${job.company}</p>
                <p>Location: ${job.location}</p>
                <button class="apply-btn" data-job-id="${job.id}">Apply Now</button>
            `;
            jobListings.appendChild(jobCard);
        });

        document.querySelectorAll(".apply-btn").forEach(button => {
            button.addEventListener("click", function () {
                applyForJob(this.dataset.jobId);
            });
        });
    }

    function applyForJob(jobId) {
        fetch("apply_job.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `job_id=${jobId}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => console.error("Error:", error));
    }

    document.querySelectorAll(".apply-btn").forEach(button => {
        button.addEventListener("click", function () {
            applyForJob(this.dataset.jobId);
        });
    });
});
