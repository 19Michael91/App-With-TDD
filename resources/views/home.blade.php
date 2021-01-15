@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="mt-8 mb-10">
            <p class="text-gray-700 mr-auto">
                <a href="{{ route('projects.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">My Projects</a>
            </p>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
