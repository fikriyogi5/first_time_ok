<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Approval</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        button {
            margin-right: 5px;
        }
        .approved {
            background-color: #d4edda;
            color: #155724;
        }
        .rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .canceled {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
    <script>
        async function fetchApprovalData() {
            const response = await fetch('approval_handler.php?action=readPending');
            const data = await response.json();
            const tableBody = document.getElementById('approval-table-body');
            tableBody.innerHTML = '';

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.name}</td>
                    <td>${row.gender}</td>
                    <td>${row.category}</td>
                    <td>${row.dob}</td>
                    <td>${row.image ? `<img src="${row.image}" width="50">` : 'N/A'}</td>
                    <td>${row.file ? `<a href="${row.file}" target="_blank">Download</a>` : 'N/A'}</td>
                    <td id="status-${row.id}">${row.status}</td>
                    <td>
                        <button onclick="updateStatus(${row.id}, 'approved')" class="approved">Approve</button>
                        <button onclick="updateStatus(${row.id}, 'rejected')" class="rejected">Reject</button>
                        <button onclick="updateStatus(${row.id}, 'canceled')" class="canceled">Cancel</button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        }

        async function updateStatus(id, status) {
            const response = await fetch(`approval_handler.php?action=updateStatus`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, status })
            });

            if (response.ok) {
                const statusCell = document.getElementById(`status-${id}`);
                statusCell.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusCell.className = status;
                fetchApprovalData(); // Refresh data
            } else {
                alert('Failed to update status. Please try again.');
            }
        }

        window.onload = fetchApprovalData;
    </script>
</head>
<body>
    <h1>Data Approval</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Category</th>
                <th>Date of Birth</th>
                <th>Image</th>
                <th>File</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="approval-table-body">
            <!-- Data will be dynamically inserted here -->
        </tbody>
    </table>
</body>
</html>
