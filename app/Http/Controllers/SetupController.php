<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Symfony\Component\Process\Process;

class SetupController extends Controller
{
    /**
     * The commands available to run from the browser.
     * Each entry defines the label, the type (artisan or shell),
     * and whether it is destructive (requires extra confirmation).
     */
    public static array $commands = [
        'composer-install' => [
            'label'       => 'Composer Install',
            'description' => 'Install / update PHP dependencies',
            'type'        => 'shell',
            'cmd'         => ['composer', 'install', '--optimize-autoloader', '--no-interaction'],
            'destructive' => false,
        ],
        'npm-install' => [
            'label'       => 'NPM Install',
            'description' => 'Install Node dependencies',
            'type'        => 'shell',
            'cmd'         => ['npm', 'install'],
            'destructive' => false,
        ],
        'npm-build' => [
            'label'       => 'Build Frontend Assets',
            'description' => 'Compile JS/CSS with Vite (npm run build)',
            'type'        => 'shell',
            'cmd'         => ['npm', 'run', 'build'],
            'destructive' => false,
        ],
        'key-generate' => [
            'label'       => 'Generate App Key',
            'description' => 'php artisan key:generate',
            'type'        => 'artisan',
            'cmd'         => 'key:generate',
            'args'        => ['--ansi' => true],
            'destructive' => false,
        ],
        'migrate' => [
            'label'       => 'Run Migrations',
            'description' => 'php artisan migrate',
            'type'        => 'artisan',
            'cmd'         => 'migrate',
            'args'        => ['--force' => true],
            'destructive' => false,
        ],
        'migrate-fresh' => [
            'label'       => 'Fresh Migration (Wipe DB)',
            'description' => 'php artisan migrate:fresh — drops all tables first',
            'type'        => 'artisan',
            'cmd'         => 'migrate:fresh',
            'args'        => ['--force' => true],
            'destructive' => true,
        ],
        'db-seed' => [
            'label'       => 'Seed Database',
            'description' => 'php artisan db:seed',
            'type'        => 'artisan',
            'cmd'         => 'db:seed',
            'args'        => ['--force' => true],
            'destructive' => true,
        ],
        'storage-link' => [
            'label'       => 'Create Storage Link',
            'description' => 'php artisan storage:link',
            'type'        => 'artisan',
            'cmd'         => 'storage:link',
            'args'        => [],
            'destructive' => false,
        ],
        'filament-upgrade' => [
            'label'       => 'Upgrade Filament Assets',
            'description' => 'php artisan filament:upgrade',
            'type'        => 'artisan',
            'cmd'         => 'filament:upgrade',
            'args'        => [],
            'destructive' => false,
        ],
        'cache-clear' => [
            'label'       => 'Clear All Caches',
            'description' => 'php artisan optimize:clear',
            'type'        => 'artisan',
            'cmd'         => 'optimize:clear',
            'args'        => [],
            'destructive' => false,
        ],
        'config-cache' => [
            'label'       => 'Cache Config',
            'description' => 'php artisan config:cache',
            'type'        => 'artisan',
            'cmd'         => 'config:cache',
            'args'        => [],
            'destructive' => false,
        ],
        'route-cache' => [
            'label'       => 'Cache Routes',
            'description' => 'php artisan route:cache',
            'type'        => 'artisan',
            'cmd'         => 'route:cache',
            'args'        => [],
            'destructive' => false,
        ],
        'view-cache' => [
            'label'       => 'Cache Views',
            'description' => 'php artisan view:cache',
            'type'        => 'artisan',
            'cmd'         => 'view:cache',
            'args'        => [],
            'destructive' => false,
        ],
    ];

    /**
     * Render the setup dashboard page.
     */
    public function index()
    {
        $commands = collect(self::$commands)->map(fn ($c, $key) => [
            'key'         => $key,
            'label'       => $c['label'],
            'description' => $c['description'],
            'destructive' => $c['destructive'],
        ])->values();

        return Inertia::render('Setup/Index', [
            'commands' => $commands,
        ]);
    }

    /**
     * Run a command and stream the output back as plain text (SSE-style).
     */
    public function run(Request $request)
    {
        $request->validate([
            'command'   => 'required|string|in:' . implode(',', array_keys(self::$commands)),
            'confirmed' => 'boolean',
        ]);

        $key     = $request->input('command');
        $config  = self::$commands[$key];

        // Destructive commands require explicit confirmation
        if ($config['destructive'] && !$request->boolean('confirmed')) {
            return response()->json([
                'error' => 'This command is destructive. Send confirmed=true to proceed.',
            ], 422);
        }

        // Stream output back to the browser
        return response()->stream(function () use ($config) {
            if ($config['type'] === 'artisan') {
                $exitCode = Artisan::call($config['cmd'], $config['args'] ?? []);
                $output   = Artisan::output();

                // Strip ANSI colour codes for clean browser display
                $output = preg_replace('/\x1B\[[0-9;]*[mGKHF]/u', '', $output);

                echo $output ?: "Done (exit code: {$exitCode})\n";
            } else {
                // Shell command — stream line by line
                $process = new Process($config['cmd'], base_path());
                $process->setTimeout(300);
                $process->start();

                foreach ($process as $type => $data) {
                    $clean = preg_replace('/\x1B\[[0-9;]*[mGKHF]/u', '', $data);
                    echo $clean;
                    ob_flush();
                    flush();
                }

                if (!$process->isSuccessful()) {
                    echo "\n[ERROR] Process exited with code " . $process->getExitCode() . "\n";
                }
            }
        }, 200, [
            'Content-Type'      => 'text/plain; charset=UTF-8',
            'X-Accel-Buffering' => 'no',
            'Cache-Control'     => 'no-cache',
        ]);
    }
}
