<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Exception;

class InstallController extends Controller
{
    public function __construct()
    {
        // Check if already installed
        if (file_exists(storage_path('installed'))) {
            abort(403, 'Application is already installed');
        }
        
        // Disable database requirement for installer routes
        // This allows the installer to run even if DB credentials are wrong
    }

    public function index()
    {
        return view('install.welcome');
    }

    public function requirements()
    {
        $requirements = [
            'php' => [
                'name' => 'PHP >= 8.2',
                'met' => version_compare(PHP_VERSION, '8.2', '>='),
            ],
            'extensions' => [
                [
                    'name' => 'BCMath Extension',
                    'met' => extension_loaded('bcmath'),
                ],
                [
                    'name' => 'Ctype Extension',
                    'met' => extension_loaded('ctype'),
                ],
                [
                    'name' => 'JSON Extension',
                    'met' => extension_loaded('json'),
                ],
                [
                    'name' => 'Mbstring Extension',
                    'met' => extension_loaded('mbstring'),
                ],
                [
                    'name' => 'OpenSSL Extension',
                    'met' => extension_loaded('openssl'),
                ],
                [
                    'name' => 'PDO Extension',
                    'met' => extension_loaded('pdo'),
                ],
                [
                    'name' => 'Tokenizer Extension',
                    'met' => extension_loaded('tokenizer'),
                ],
                [
                    'name' => 'XML Extension',
                    'met' => extension_loaded('xml'),
                ],
                [
                    'name' => 'GD Extension',
                    'met' => extension_loaded('gd'),
                ],
                [
                    'name' => 'cURL Extension',
                    'met' => extension_loaded('curl'),
                ],
                [
                    'name' => 'Zip Extension',
                    'met' => extension_loaded('zip'),
                ],
            ],
            'permissions' => [
                [
                    'name' => 'storage/',
                    'met' => is_writable(storage_path()),
                ],
                [
                    'name' => 'bootstrap/cache/',
                    'met' => is_writable(base_path('bootstrap/cache')),
                ],
            ],
        ];

        return view('install.requirements', compact('requirements'));
    }

    public function database()
    {
        return view('install.database');
    }

    public function testDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required|numeric',
            'db_name' => 'required',
            'db_username' => 'required',
        ]);

        try {
            config([
                'database.connections.test' => [
                    'driver' => 'mysql',
                    'host' => $request->db_host,
                    'port' => $request->db_port,
                    'database' => $request->db_name,
                    'username' => $request->db_username,
                    'password' => $request->db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]
            ]);

            DB::connection('test')->getPdo();

            return response()->json(['success' => true, 'message' => 'Database connection successful!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function saveDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required|numeric',
            'db_name' => 'required',
            'db_username' => 'required',
        ]);

        try {
            // Update .env file
            $this->updateEnv([
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_name,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password,
            ]);

            // Clear config cache
            Artisan::call('config:clear');

            // Store database config in session for next steps
            session([
                'install_db_config' => $request->only(['db_host', 'db_port', 'db_name', 'db_username', 'db_password'])
            ]);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function migrate()
    {
        try {
            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            return response()->json(['success' => true, 'message' => 'Database tables created successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function settings()
    {
        return view('install.settings');
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'discord_url' => 'nullable|url|max:255',
            'load_demo_data' => 'nullable|boolean',
        ]);

        try {
            // Save site settings to database
            \App\Models\SiteSetting::set('site_name', $request->site_name);
            \App\Models\SiteSetting::set('site_description', $request->site_description ?? '');
            \App\Models\SiteSetting::set('facebook_url', $request->facebook_url ?? '');
            \App\Models\SiteSetting::set('twitter_url', $request->twitter_url ?? '');
            \App\Models\SiteSetting::set('discord_url', $request->discord_url ?? '');

            // Store demo data preference in session
            session(['install_load_demo_data' => $request->load_demo_data]);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function admin()
    {
        return view('install.admin');
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Create admin user
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'email_verified_at' => now(),
            ]);

            // Create wallet
            $user->wallet()->create(['balance' => 0]);

            // Create Super Admin role (Spatie tables already created by migration)
            $role = \Spatie\Permission\Models\Role::firstOrCreate(
                ['name' => 'Super Admin'],
                ['guard_name' => 'web']
            );
            $user->assignRole($role);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function finalize()
    {
        try {
            // Generate APP_KEY if not set
            if (empty(env('APP_KEY'))) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            // Storage link
            Artisan::call('storage:link');
            
            // Publish Filament assets
            Artisan::call('filament:assets');

            // Load demo data if requested
            if (session('install_load_demo_data')) {
                Artisan::call('db:seed', ['--force' => true]);
            }

            // Create installed file
            File::put(storage_path('installed'), now()->toString());

            // Clear all caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            // Clear session data
            session()->forget(['install_db_config', 'install_load_demo_data']);

            return view('install.complete');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function updateEnv(array $data)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            // Copy from .env.example
            File::copy(base_path('.env.example'), $envPath);
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            // Escape special characters
            $value = str_replace('"', '\"', $value);
            
            // Check if key exists
            if (preg_match("/^{$key}=/m", $envContent)) {
                // Update existing
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                // Add new
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
