@extends('installer::layout')

@section('content')
    <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
        <h2 class="card-title text-center mb-3">Create Admin User <i class="bi bi-person-fill-add ms-2"></i></h2>
        <p class="card-text text-center text-muted mb-4">
            Please create an admin user for your application. This will be your primary login.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('installer.admin.save') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                <div class="form-text text-muted">Enter your full name or preferred username.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
                <div class="form-text text-muted">This username will be admin for login.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                <div class="form-text text-muted">This email will be used for login.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <div class="form-text text-muted">Choose a strong password (min. 8 characters recommended).</div>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('installer.database') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i> Back</a>
                <button type="submit" class="btn btn-primary">Create Admin & Finish <i class="bi bi-check-circle-fill ms-2"></i></button>
            </div>
        </form>
    </div>
@endsection
