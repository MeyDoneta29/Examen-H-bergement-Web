<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = auth()->user()->tasks()->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        auth()->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Tâche créée avec succès!');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Tâche modifiée avec succès!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tâche supprimée avec succès!');
    }

    public function toggle(Task $task)
    {
        $this->authorize('update', $task);
        $task->update(['is_completed' => !$task->is_completed]);

        return redirect()->route('tasks.index')
            ->with('success', 'Statut mis à jour!');
    }
}