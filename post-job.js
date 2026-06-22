document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("post-job-form");

    form.addEventListener("submit", function (event) {
        let title = form.querySelector('input[name="title"]').value.trim();
        let company = form.querySelector('input[name="company"]').value.trim();
        let location = form.querySelector('input[name="location"]').value.trim();
        let description = form.querySelector('textarea[name="description"]').value.trim();

        if (title === "" || company === "" || location === "" || description === "") {
            alert("All fields are required.");
            event.preventDefault(); // Prevent form submission
        }
    });
});
