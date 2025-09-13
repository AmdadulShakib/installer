<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer @hasSection('title') - @yield('title') @endif</title>

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        body {
            background: linear-gradient(to right, #ADD8E6 0%, #E0FFFF 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .installer-steps-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }

        .installer-steps {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .installer-steps li {
            padding: 5px 15px;
            color: #6c757d;
            font-weight: bold;
            position: relative;
            font-size: 0.9rem;
        }
        .installer-steps li.active {
            color: #0d6efd;
        }
        .installer-steps li.completed {
            color: #198754;
        }
        .installer-steps li + li::before {
            content: "\203A";
            position: absolute;
            left: -5px;
            color: #adb5bd;
        }
    </style>
</head>
<body>
    @php
        $currentRoute = Route::currentRouteName();
        $steps = [
            'installer.welcome' => ['name' => 'Welcome', 'order' => 1],
            'installer.verify' => ['name' => 'Purchase Code', 'order' => 2],
            'installer.requirements' => ['name' => 'Requirements', 'order' => 3],
            'installer.environment' => ['name' => 'Environment', 'order' => 4],
            'installer.database' => ['name' => 'Database', 'order' => 5],
            'installer.admin' => ['name' => 'Admin User', 'order' => 6],
            'installer.complete' => ['name' => 'Complete', 'order' => 7],
        ];
        $currentOrder = $steps[$currentRoute]['order'] ?? 1;
    @endphp

    <div class="container mt-4">
        <div class="installer-steps-card">
            <ul class="installer-steps">
                @foreach($steps as $route => $step)
                    <li class="{{ $currentOrder >= $step['order'] ? 'completed' : '' }} {{ $currentRoute == $route ? 'active' : '' }}">
                        {{ $step['name'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
