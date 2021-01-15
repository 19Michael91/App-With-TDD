<nav class="bg-white">
    <div class="container mx-auto">
        <div class="flex justify-between items-center py-2">
            <h1>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('/images/logo.svg') }}" alt="Birdboard">
                </a>
            </h1>

            <div>
                <div class="flex items-center ml-auto">
                    @guest
                        <a class="text-accent mr-4 no-underline hover:underline" href="{{ route('login') }}">{{ __('Login') }}</a>

                        @if (Route::has('register'))
                            <a class="text-accent no-underline hover:underline" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    @else

                        <dropdown align="right" width="200px">
                            <template v-slot:trigger>
                                <button
                                    class="flex items-center text-default no-underline text-sm focus:outline-none"
                                    v-pre
                                >
                                    <img width="35"
                                         class="rounded-full mr-3"
                                         src="https://gravatar.com/avatar/{{ md5(Auth::user()->email)  }}?s=90&d=https://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg">

                                    {{ auth()->user()->name }}
                                </button>
                            </template>

                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="dropdown-menu-link w-full text-left" style="text-align: center;">Logout</button>
                            </form>
                        </dropdown>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</nav>
