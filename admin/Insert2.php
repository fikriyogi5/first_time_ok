<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Interface</title>
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
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            margin-bottom: 10px;
        }
    </style>
    <script>
        async function fetchData() {
            const response = await fetch('crud_handler.php?action=read');
            const data = await response.json();
            const tableBody = document.getElementById('data-table-body');
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
                    <td>
                        <button onclick="editRow(${row.id})">Edit</button>
                        <button onclick="deleteRow(${row.id})">Delete</button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        }

        async function submitForm(e) {
            e.preventDefault();
            const formData = new FormData(document.getElementById('crud-form'));
            await fetch('crud_handler.php', { method: 'POST', body: formData });
            document.getElementById('crud-form').reset();
            fetchData();
        }

        async function deleteRow(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                await fetch(`crud_handler.php?action=delete&id=${id}`);
                fetchData();
            }
        }

        async function editRow(id) {
            const response = await fetch(`crud_handler.php?action=readOne&id=${id}`);
            const data = await response.json();
            document.getElementById('id').value = data.id;
            document.getElementById('name').value = data.name;
            document.querySelector(`input[name="gender"][value="${data.gender}"]`).checked = true;
            document.getElementById('category').value = data.category;
            document.getElementById('dob').value = data.dob;
        }

        window.onload = fetchData;
    </script>
</head>
<body>
    <h1>CRUD Interface</h1>

    <!-- Form Section -->
    <form id="crud-form" onsubmit="submitForm(event)" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id">
        <label for="name">Name: <input type="text" name="name" id="name" required></label>
        <label>Gender:
            <input type="radio" name="gender" value="Male" required> Male
            <input type="radio" name="gender" value="Female" required> Female
        </label>
        <label for="category">Category:
            <select name="category" id="category" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
            </select>
        </label>
        <label for="dob">Date of Birth: <input type="date" name="dob" id="dob" required></label>
        <label for="image">Image: <input type="file" name="image" id="image"></label>
        <label for="file">File: <input type="file" name="file" id="file"></label>
        <button type="submit">Save</button>
    </form>

    <!-- Table Section -->
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="data-table-body">
            <!-- Data will be dynamically inserted here -->
        </tbody>
    </table>
</body>
</html>
