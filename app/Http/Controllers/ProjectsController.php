<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;

class ProjectsController extends Controller
{
	public function index()
    {
    	$projects = auth()->user()->accessibleProjects();

    	return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'min:3|max:255',
        ]);

        $project = auth()->user()->projects()->create($attributes);

        if(request()->has('tasks')){
            $project->addTasks(request('tasks'));
        }

        if(request()->wantsJson()){
            return ['message' => $project->path()];
        }

    	return redirect()->route('projects.show', ['project' => $project->id]);
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request)
    {
        return redirect()->route('projects.show', ['project' => $request->save()->id]);
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();

        return redirect()->route('projects.index');
    }
}
