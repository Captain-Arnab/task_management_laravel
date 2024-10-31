<?php 

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Start with a query on the Task model
        $tasks = Task::query();
    
        // Apply default sorting by priority
        $tasks->orderBy('priority', 'asc');
    
        // Optional sorting by any specified column (e.g., by title or due date)
        if ($request->filled('sort_by')) {
            $tasks->orderBy($request->input('sort_by'), $request->input('sort_direction', 'asc'));
        }
    
        // Apply search filter if search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $tasks->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%');
        }
    
        // Use pagination to manage the list display
        $tasks = $tasks->paginate(10);
    
        return view('tasks.index', ['tasks' => $tasks]);
    }
    

    public function create()
    {
        return view('tasks.create');
    }

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

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

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
