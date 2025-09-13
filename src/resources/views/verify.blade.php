@extends('installer::layout')

@section('content')
    <div class="card shadow-lg p-5 text-center" style="max-width: 500px; width: 100%;">
        <h2 class="card-title mb-3">Purchase Code Verification</h2>
        <p class="text-muted mb-4">Please enter your Envato purchase code to continue.</p>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first('purchase_code') }}</div>
        @endif

        <form method="POST" action="{{ route('installer.verify.post') }}">
            @csrf
            <div class="mb-3">
                <input type="text" name="purchase_code" class="form-control" placeholder="Enter your purchase code" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                Verify & Continue <i class="bi bi-arrow-right"></i>
            </button>
        </form>
    </div>
@endsection
