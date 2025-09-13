@extends('installer::layout')

@section('content')
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%;">
        <h2 class="card-title text-center mb-3">Server Requirements</h2>
        <p class="card-text text-center text-muted mb-4">
            Please ensure all requirements are met to proceed with the installation.
        </p>
        <ul class="list-group list-group-flush">
            @foreach($requirements as $requirement => $status)
                <li class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                    <span>
                        <i class="bi bi-gear me-2"></i>
                        <span class="fw-bold">{{ $requirement }}</span>
                    </span>
                    @if($status)
                        <span class="badge bg-success rounded-pill">Passed <i class="bi bi-check-circle-fill ms-1"></i></span>
                    @else
                        <span class="badge bg-danger rounded-pill">Failed <i class="bi bi-x-circle-fill ms-1"></i></span>
                    @endif
                </li>
            @endforeach
        </ul>
        <div class="text-center mt-4">
            @php
                $allPassed = true;
                foreach ($requirements as $status) {
                    if (!$status) {
                        $allPassed = false;
                        break;
                    }
                }
            @endphp

            @if($allPassed)
                <a href="{{ route('installer.environment') }}" class="btn btn-primary px-5">Next <i class="bi bi-arrow-right ms-2"></i></a>
            @else
                <button type="button" class="btn btn-secondary px-5" disabled>Next <i class="bi bi-arrow-right ms-2"></i></button>
                <p class="text-danger mt-2">All requirements must be passed to proceed.</p>
            @endif
        </div>
    </div>
@endsection
