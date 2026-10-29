<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Tasks fetched successfully.',
            'data' => Task::with('user')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $validated['user_id'] = $validated['user_id'] ?? $request->user()->id;

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'data' => $task->load('user'),
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'progress' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'status' => ['sometimes', 'in:pending,in_progress,completed'],
            'user_id' => ['sometimes', 'nullable', 'exists:users,id'],
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully.',
            'data' => $task->fresh(),
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }
}
