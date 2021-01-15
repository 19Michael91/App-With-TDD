<div class="card flex flex-col mt-5">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-500 pl-4">
        Invite a User
    </h3>
    <form action="{{ route('projects.invitations.store', ['project' => $project]) }}" method="POST">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="border border-gray-600 rounded w-full py-2 px-3" placeholder="Email address">
        </div>
        <button type="submit" class="button text-xs hover:bg-blue-600 hover:text-white">Invite</button>
    </form>
    @include('errors', ['bag' => 'invitations'])
</div>
