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

    public function verifyForm()
    {
        return view('installer::verify');
    }

    public function verifyPurchase(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string',
        ]);

        $purchaseCode = $request->purchase_code;
        $token = config('services.authorization.token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.envato.com/v3/market/author/sale?code=" . $purchaseCode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . base64_decode($token),
            "User-Agent: Laravel Installer"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $result = json_decode($response, true);

            if (isset($result['buyer'])) {
                \Illuminate\Support\Facades\File::put(storage_path('purchase_verified.lock'), now());

                return redirect()->route('installer.requirements')->with('success', 'Purchase code verified successfully!');
            }
        }

        return back()->withErrors(['purchase_code' => 'Invalid or expired purchase code.']);
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

        $sqlPath = dirname(base_path()) . '/database/database.sql';

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
            'username' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $fullName = trim($request->name);
        $nameParts = explode(' ', $fullName);
        $firstName = array_shift($nameParts);
        $lastName = implode(' ', $nameParts);

        \App\Models\Admin::create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'is_admin'   => true,
        ]);

        File::put(storage_path('installed.lock'), now());

        return redirect()->route('installer.finish');
    }

    public function finish()
    {
        return view('installer::finish');
    }
}
