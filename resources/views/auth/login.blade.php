<!-- @extends('layouts.app') -->

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Sign In</h2>

    <!-- Display error message if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>
    </form>
</div>
@endsection

