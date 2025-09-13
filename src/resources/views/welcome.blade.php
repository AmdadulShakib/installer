@extends('installer::layout')

@section('content')
    <div class="card shadow-lg p-5 text-center" style="max-width: 500px; width: 100%;">
        <h2 class="card-title mb-3">Welcome to the Installer</h2>

        <p class="card-text text-muted mb-4">
            This wizard will guide you through the quick and easy installation process of your new application.
        </p>

        <a href="{{ route('installer.verify') }}" class="btn btn-primary px-5">
            Next Step <i class="bi bi-arrow-right"></i>
        </a>
    </div>
@endsection
