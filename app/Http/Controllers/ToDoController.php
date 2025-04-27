<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class ToDoController extends Controller
{
    public function index()
    {
        $tasks = Task::where('completed', 0)->latest()->get(); // Only incomplete tasks
        return view('todo.index', compact('tasks'));
    }
    public function fetchTasks(Request $request)
    {
        $showAll = $request->input('show_all', false);

        if ($showAll) {
            $tasks = Task::latest()->get(); // get all tasks
        } else {
            $tasks = Task::where('completed', 0)->latest()->get(); // only incomplete
        }

        return response()->json([
            'tasks' => $tasks
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
        ]);

        // Check if task with same name already exists
        if (Task::where('task_name', $request->task_name)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task with the same name already exists.'
            ], 409); // 409 Conflict
        }

        $task = Task::create([
            'task_name' => $request->task_name,
            'completed' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'task' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found.'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully.'
        ]);
    }

    public function toggleComplete($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found.'
            ], 404);
        }

        $task->completed = !$task->completed;
        $task->save();

        return response()->json([
            'status' => 'success',
            'task' => $task
        ]);
    }
}
