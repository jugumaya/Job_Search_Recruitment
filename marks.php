<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marks Table</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 40px auto;
        }
        th, td {
            padding: 10px;
            border: 1px solid #999;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Marks Evaluation Table</h2>
    <div id="marks-table"></div>

    <script>
        const data = [
            {
                mark: "4",
                justification: "job giver can post jobs, the details is stored in backend using job table",
                internal: "post-job.php"
            },
            {
                mark: "3",
                justification: "fetch all the jobs from the job table we can search job .",
                internal: "browsejob.php"
            },
            
            {
                mark: "3",
                justification: "you can view all the jobs you have applied for and view the status if you are accepted of not fetch all the application from the backend and made the application table for this in the database ",
                internal: "applied-jobs.php"
            },
            {
                mark: "2",
                justification: "you can withdraw your application and it delete the application from the applications table",
                internal: "withdraw_applicants.php"
            },
            {
                mark: "3",
                justification: "you can view the jobs you have created and you can delete it , if deleted it will delete it from the databasde ",
                internal: "manage-jobs.php"
            }
        ];

        const table = d3.select("#marks-table")
            .append("table");

        const thead = table.append("thead");
        const tbody = table.append("tbody");

        // Define columns
        const columns = ["mark", "justification", "internal"];

        // Append headers
        thead.append("tr")
            .selectAll("th")
            .data(columns)
            .enter()
            .append("th")
            .text(d => d);

        // Append rows and data
        const rows = tbody.selectAll("tr")
            .data(data)
            .enter()
            .append("tr");

        rows.selectAll("td")
            .data(row => columns.map(col => row[col]))
            .enter()
            .append("td")
            .text(d => d);
    </script>
</body>
</html>
