@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <p class="text-gray-700 mr-auto">
            <a href="{{ route('projects.index') }}" class="mr-auto no-underline hover:text-gray-500">My Projects</a> / {{ $project->title }}
        </p>

        <div class="flex items-center">
            @foreach($project->members as $member)
                <img src="https://gravatar.com/avatar/{{ md5($member->email)  }}?s=60&d=https://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg"
                     alt="{{ $member->name }}'s avatar'"
                     class="rounded-full w-8 inline-block mr-2">
            @endforeach

            <img src="https://gravatar.com/avatar/{{ md5($project->owner->email)  }}?s=60&d=https://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg"
                 alt="{{ $project->owner->email }}'s avatar'"
                 class="rounded-full w-8 inline-block mr-2">

            <a href="{{ route('projects.edit', ['project' => $project]) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Edit Project</a>
        </div>

    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-700 mr-auto mb-3">
                        Tasks
                    </h2>

                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{ route('projects.tasks.update', ['project' => $project->id, 'task' => $task->id,]) }}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input class="w-full {{$task->completed ? 'text-gray-500' : ''}}" name="body" value="{{ $task->body }}">
                                    <input type="checkbox" class="mt-1" name="completed" onChange="this.form.submit()" {{ $task->completed ? 'checked' : ''}}>
                                </div>
                            </form>
                        </div>
                    @endforeach

                    <div class="card mb-3">
                        <form action="{{ route('projects.tasks.store', ['project' => $project->id]) }}" method="POST">
                            @csrf
                            <input placeholder="Begin adding tasks..." class="w-full" name="body">
                        </form>
                    </div>

                </div>

                <div>
                    <h2 class="text-lg text-gray-700 mr-auto mb-3">
                        General Notes
                    </h2>

                    <form action="" method="POST">
                        @method('PATCH')
                        @csrf
                        <textarea
                            name="notes"
                            class="card w-full mb-4"
                            style="min-height: 200px;"
                            placeholder="Anything special that you want to make a note of?"
                        >{{ $project->notes }}
                        </textarea>

                        <button class="button is-link" type="submit">
                            Save
                        </button>
                    </form>

                    @include('errors')
                </div>
            </div>

            <div class="lg:w-1/4 p-3">
                <div class="px-3">
                    @include('projects.card')

                    @include('projects.activity.card')

                    @can('manage', $project)
                        @include('projects.invite')
                    @endcan

                </div>
            </div>
        </div>
    </main>

@endsection
