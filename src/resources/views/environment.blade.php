@extends('installer::layout')

@section('content')
    <div class="card shadow-lg p-4" style="max-width: 700px; width: 100%;">
        <h2 class="card-title text-center mb-4">Environment Setup</h2>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('installer.environment.save') }}">
            @csrf

            <ul class="nav nav-tabs mb-3" id="envTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="app-tab" data-bs-toggle="tab" data-bs-target="#appSettings" type="button" role="tab" aria-controls="appSettings" aria-selected="true">
                        <i class="bi bi-app-indicator me-2"></i>Application Settings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="db-tab" data-bs-toggle="tab" data-bs-target="#dbSettings" type="button" role="tab" aria-controls="dbSettings" aria-selected="false">
                        <i class="bi bi-database me-2"></i>Database Settings
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="envTabsContent">
                {{-- Application Settings Tab Pane --}}
                <div class="tab-pane fade show active" id="appSettings" role="tabpanel" aria-labelledby="app-tab">
                    <div class="mb-3">
                        <label for="appName" class="form-label">App Name</label>
                        <input type="text" name="APP_NAME" id="appName" class="form-control" value="{{ old('APP_NAME', env('APP_NAME', 'Laravel')) }}" required>
                        <div class="form-text text-muted">A user-friendly name for your application.</div>
                    </div>
                    <div class="mb-3">
                        <label for="appEnv" class="form-label">App Environment</label>
                        <input type="text" name="APP_ENV" id="appEnv" class="form-control" value="{{ old('APP_ENV', env('APP_ENV', 'local')) }}" required>
                        <div class="form-text text-muted">e.g., 'local', 'staging', 'production'.</div>
                    </div>
                    <div class="mb-3">
                        <label for="appDebug" class="form-label">Debug Mode</label>
                        <select name="APP_DEBUG" id="appDebug" class="form-select">
                            <option value="true" {{ (old('APP_DEBUG', env('APP_DEBUG')) == 'true') ? 'selected' : '' }}>True</option>
                            <option value="false" {{ (old('APP_DEBUG', env('APP_DEBUG')) == 'false') ? 'selected' : '' }}>False</option>
                        </select>
                        <div class="form-text text-muted">Set to 'False' for production environments.</div>
                    </div>
                </div>

                {{-- Database Settings Tab Pane --}}
                <div class="tab-pane fade" id="dbSettings" role="tabpanel" aria-labelledby="db-tab">
                    <div class="mb-3">
                        <label for="dbConnection" class="form-label">DB Connection</label>
                        <input type="text" name="DB_CONNECTION" id="dbConnection" class="form-control" value="{{ old('DB_CONNECTION', env('DB_CONNECTION', 'mysql')) }}" required>
                        <div class="form-text text-muted">e.g., 'mysql', 'pgsql', 'sqlite'.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dbHost" class="form-label">DB Host</label>
                        <input type="text" name="DB_HOST" id="dbHost" class="form-control" value="{{ old('DB_HOST', env('DB_HOST', '127.0.0.1')) }}" required>
                        <div class="form-text text-muted">The IP address or hostname of your database server.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dbPort" class="form-label">DB Port</label>
                        <input type="text" name="DB_PORT" id="dbPort" class="form-control" value="{{ old('DB_PORT', '3306') }}" required>
                        <div class="form-text text-muted">The port number of your database server.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dbDatabase" class="form-label">DB Database</label>
                        <input type="text" name="DB_DATABASE" id="dbDatabase" class="form-control" value="{{ old('DB_DATABASE', '') }}" required>
                        <div class="form-text text-muted">The name of the database.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dbUsername" class="form-label">DB Username</label>
                        <input type="text" name="DB_USERNAME" id="dbUsername" class="form-control" value="{{ old('DB_USERNAME', '') }}" required>
                        <div class="form-text text-muted">The username for database access.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dbPassword" class="form-label">DB Password</label>
                        <input type="password" name="DB_PASSWORD" id="dbPassword" class="form-control" value="{{ old('DB_PASSWORD', '') }}">
                        <div class="form-text text-muted">The password for the database user. (Optional if no password)</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('installer.requirements') }}" class="btn btn-dark btn-lg px-4"><i class="bi bi-arrow-left me-2"></i> Back</a>
                <button type="submit" class="btn btn-primary btn-lg px-5">Save & Next <i class="bi bi-arrow-right ms-2"></i></button>
            </div>
        </form>
    </div>
@endsection