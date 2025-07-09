<?php

namespace amdadulshakib\installer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function welcome()
    {
        return view('installer::welcome');
    }

    public function requirements()
    {
        $requirements = [
            'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'OpenSSL PHP Extension' => extension_loaded('openssl'),
            'PDO PHP Extension' => extension_loaded('pdo'),
            'Mbstring PHP Extension' => extension_loaded('mbstring'),
            'Tokenizer PHP Extension' => extension_loaded('tokenizer'),
            'XML PHP Extension' => extension_loaded('xml'),
            'BCMath PHP Extension' => extension_loaded('bcmath'),
            'Ctype PHP Extension' => extension_loaded('ctype'),
            'JSON PHP Extension' => extension_loaded('json'),
        ];

        return view('installer::requirements', compact('requirements'));
    }

    public function environmentForm()
    {
        $envPath = base_path('.env');
        $envContent = File::exists($envPath) ? File::get($envPath) : '';

        return view('installer::environment', compact('envContent'));
    }

    public function saveEnvironment(Request $request)
    {
        $data = $request->only([
            'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG',
            'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
        ]);

        $envPath = base_path('.env');
        $envContent = File::exists($envPath) ? File::get($envPath) : '';

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$value}";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        File::put($envPath, $envContent);

        // Cache config clear
        Artisan::call('config:clear');

        return redirect()->route('installer.database');
    }

    public function databaseForm()
    {
        return view('installer::database');
    }

    public function saveDatabase(Request $request)
    {
        config([
            'database.connections.temp' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]);

        try {
            DB::connection('temp')->getPdo();
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Could not connect to the database: ' . $e->getMessage()]);
        }

        $existingTables = DB::connection('temp')->select('SHOW TABLES');
        if (count($existingTables) > 0) {
            return redirect()->route('installer.admin');
        }

        $sqlPath = base_path('mysql/database.sql');

        if (!file_exists($sqlPath)) {
            return back()->withErrors(['sql_error' => 'SQL file not found at: mysql/database.sql']);
        }

        $sqlContent = file_get_contents($sqlPath);

        try {
            DB::connection('temp')->unprepared($sqlContent);
        } catch (\Exception $e) {
            return back()->withErrors(['sql_error' => 'SQL import failed: ' . $e->getMessage()]);
        }


//Artisan::call('migrate', ['--force' => true]); //install er somoy dorkar nai

        return redirect()->route('installer.admin');
    }

    public function adminForm()
    {
        return view('installer::admin');
    }

    public function saveAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        File::put(storage_path('installed.lock'), now());

        return redirect()->route('installer.finish');
    }

    public function finish()
    {
        return view('installer::finish');
    }
}
