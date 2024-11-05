<?php 

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    // Displays the initial view for tasks with DataTables
    public function index()
    {
        return view('tasks.index');
    }
    
    // Returns JSON data for DataTables with server-side processing
    public function getData(Request $request)
    {
        $query = Task::query();
    
        // Apply search filter if a search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%')
                  ->orWhere('due_date', 'like', '%' . $searchTerm . '%')
                  ->orWhere('priority', 'like', '%' . $searchTerm . '%');
            });
        }
    
        // Apply sorting based on column and direction specified by DataTables
        if ($request->filled('order.0.column')) {
            $columns = ['priority', 'title', 'due_date', 'status'];
            $columnIndex = $request->input('order.0.column');
            $sortDirection = $request->input('order.0.dir', 'asc');
    
            // Ensure the column index exists in the columns array to avoid errors
            if (isset($columns[$columnIndex])) {
                $query->orderBy($columns[$columnIndex], $sortDirection);
            }
        } else {
            // Default sorting by priority if no specific column is specified
            $query->orderBy('priority', 'asc');
        }
    
        return DataTables::of($query)
            ->addColumn('actions', function ($task) {
                $editUrl = route('tasks.edit', $task->id);
                $deleteUrl = route('tasks.destroy', $task->id);
    
                return view('tasks.partials.actions', compact('editUrl', 'deleteUrl', 'task'))->render();
            })
            ->rawColumns(['actions']) // Render HTML in 'actions' column
            ->make(true);
    }
    
    // Display form to create a new task
    public function create()
    {
        return view('tasks.create');
    }

    // Store a new task in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'required|string',
            'priority' => 'required|integer',
        ]);

        Task::create($validated);
        
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    // Show the form to edit an existing task
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    // Update an existing task in the database
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|integer',
            'status' => 'required|string',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    // Delete a task from the database
    public function destroy($id)
    {
        Task::destroy($id);
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    // Drag-and-Drop Reorder Update
    public function reorder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
}
