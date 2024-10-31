<!-- resources/views/tasks/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Task List</h1>

    <form method="GET" action="{{ route('tasks.index') }}" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Search tasks" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Add Task</a>

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
        <tbody>
            @foreach($tasks as $task)
                <tr data-id="{{ $task->id }}">
                    <td>{{ $task->priority }}</td> <!-- Displaying priority -->
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->due_date }}</td>
                    <td>{{ $task->status }}</td>
                    <td>
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
</table>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        $("#tasks-table tbody").sortable({
            update: function(event, ui) {
                let order = $(this).sortable('toArray', { attribute: 'data-id' });
                $.ajax({
                    url: "{{ route('tasks.reorder') }}",
                    type: "POST",
                    data: {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log("Reorder successful", response);
                        // Reload the page to reflect the new order
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Reorder failed", error);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
