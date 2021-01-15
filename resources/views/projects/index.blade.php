@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <h2 class="text-gray-500 mr-auto">
            My Projects
        </h2>
        <a href="{{ route('projects.create') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded"
           v-on:click.prevent="$modal.show('new-project')">New Project</a>
    </header>

    <main class="lg:flex lg:flex-wrap -mx-3">
        @forelse ($projects as $project)
            <div class="lg:w-1/3 px-3 pb-6">
                @include('projects.card')
            </div>
        @empty
            <div>No Projects Yet.</div>
        @endforelse
    </main>

    <new-project-modal></new-project-modal>

@endsection
