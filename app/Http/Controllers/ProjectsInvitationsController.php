<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProjectsInvitationRequest;
use App\Project;
use App\User;

class ProjectsInvitationsController extends Controller
{
    public function store(Project $project, ProjectsInvitationRequest $request)
    {
        $user = User::whereEmail(request('email'))->first();

        $project->invite($user);

        return redirect()->route('projects.show', compact('project'));
    }
}
