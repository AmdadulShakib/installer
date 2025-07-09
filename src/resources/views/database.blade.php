@extends('installer::layout')

@section('content')
    <div class="card shadow-lg p-4 text-center" style="max-width: 500px; width: 100%;">
        <h2 class="card-title mb-3">Database Setup <i class="bi bi-database-check ms-2"></i></h2>
        <p class="card-text mb-4">Ensure your database connection details are correct and click "Import Database & Next" to proceed.</p>
        
        <form method="POST" action="{{ route('installer.database.save') }}">
            @csrf
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('installer.environment') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i> Back</a>
                <button type="submit" class="btn btn-primary btn-lg">Import Database & Next <i class="bi bi-arrow-right ms-2"></i></button>
            </div>
        </form>
    </div>
@endsection