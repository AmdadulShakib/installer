@extends('installer::layout')

@section('content')
    <div class="card shadow-lg p-5 text-center" style="max-width: 500px; width: 100%;">
        <h2 class="card-title text-success mb-3">Installation Complete! <i class="bi bi-check-circle-fill"></i></h2>
        <p class="card-text text-muted mb-2">Congratulations!</p>
        <p class="card-text mb-4">Your application has been installed successfully and is ready to use.</p>

        <a href="{{ url('/') }}" class="btn btn-success w-100">Go to Home <i class="bi bi-box-arrow-in-up-right ms-2"></i></a>

        <p class="card-text mt-4 text-muted">You can also visit the <a href="{{ url('/admin') }}">Admin Panel <i class="bi bi-box-arrow-in-up-right"></i></a>.</p>
    </div>
@endsection
