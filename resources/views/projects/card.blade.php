
<div class="card flex flex-col">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-500 pl-4">
        <a href="{{ $project->path() }}" class="text-black no-underline">
            {{ $project->title }}
        </a>
    </h3>

    <div class="text-gray-500 mb-4 flex-1">
        {{ str_limit($project->description, 100) }}
    </div>

    @can ('manage', $project)
        <footer>
            <form action="{{ route('projects.delete', ['project' => $project]) }}" method="POST" class="text-right">
                @method('DELETE')
                @csrf

                <button type="submit" class="button-cancel text-xs hover:bg-red-600 hover:text-white">Delete</button>
            </form>
        </footer>
    @endcan
</div>
