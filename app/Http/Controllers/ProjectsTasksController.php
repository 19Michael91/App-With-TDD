<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Task;

class ProjectsTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        $attributes = request()->validate([
            'body' => 'required'
        ]);

        $project->addTask($attributes['body']);

        return redirect()->route('projects.show', ['project' => $project->id]);
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $project);

        $task->update(request()->validate(['body' => 'required']));

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect()->route('projects.show', ['project' => $project->id]);
    }
}
