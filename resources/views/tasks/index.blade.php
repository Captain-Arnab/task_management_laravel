<!-- resources/views/tasks/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Task List</h1>

    <!-- Search Form -->
    <div class="d-flex mb-3">
        <input type="text" id="taskSearch" class="form-control me-2" placeholder="Search tasks">
        <button type="button" id="searchButton" class="btn btn-primary">Search</button>
    </div>
    
    <!-- Add Task Button -->
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Add Task</a>

    <!-- Task Table -->
    <table id="tasks-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Priority</th>
                <th>Title</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTables with server-side processing
    let table = $('#tasks-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('tasks.data') }}",
            data: function(d) {
                d.search = $('#taskSearch').val(); // Custom search term
            }
        },
        columns: [
            { data: 'priority', name: 'priority' },
            { data: 'title', name: 'title' },
            { data: 'due_date', name: 'due_date' },
            { data: 'status', name: 'status' },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false 
            }
        ]
    });

    // Trigger search on input event in the search field
    $('#taskSearch').on('input', function() {
        table.draw();
    });

    // Trigger search on button click
    $('#searchButton').on('click', function() {
        table.draw();
    });
});
</script>

</body>
</html>
