<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class HealthController extends Controller
{
    /**
     * Health check endpoint
     */
    public function check()
    {
        $status = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
            'environment' => config('app.env'),
            'checks' => []
        ];

        // Database check
        try {
            DB::connection()->getPdo();
            $status['checks']['database'] = 'healthy';
        } catch (\Exception $e) {
            $status['checks']['database'] = 'unhealthy';
            $status['status'] = 'unhealthy';
        }

        // Cache check
        try {
            Cache::put('health_check', 'ok', 60);
            if (Cache::get('health_check') === 'ok') {
                $status['checks']['cache'] = 'healthy';
            } else {
                $status['checks']['cache'] = 'unhealthy';
                $status['status'] = 'unhealthy';
            }
        } catch (\Exception $e) {
            $status['checks']['cache'] = 'unhealthy';
            $status['status'] = 'unhealthy';
        }

        // Redis check
        try {
            Redis::ping();
            $status['checks']['redis'] = 'healthy';
        } catch (\Exception $e) {
            $status['checks']['redis'] = 'unhealthy';
            $status['status'] = 'unhealthy';
        }

        // Storage check
        try {
            $testFile = storage_path('app/health_test.txt');
            file_put_contents($testFile, 'test');
            unlink($testFile);
            $status['checks']['storage'] = 'healthy';
        } catch (\Exception $e) {
            $status['checks']['storage'] = 'unhealthy';
            $status['status'] = 'unhealthy';
        }

        // Memory usage
        $status['memory_usage'] = memory_get_usage(true);
        $status['memory_peak'] = memory_get_peak_usage(true);

        // Disk usage
        $status['disk_usage'] = disk_free_space(storage_path()) / disk_total_space(storage_path()) * 100;

        $httpCode = $status['status'] === 'healthy' ? 200 : 503;

        return response()->json($status, $httpCode);
    }

    /**
     * Detailed health check for monitoring
     */
    public function detailed()
    {
        $details = [
            'application' => [
                'name' => config('app.name'),
                'environment' => config('app.env'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
            ],
            'database' => [
                'connection' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
            ],
            'cache' => [
                'driver' => config('cache.default'),
                'prefix' => config('cache.prefix'),
            ],
            'queue' => [
                'connection' => config('queue.default'),
                'retry_after' => config('queue.connections.redis.retry_after'),
            ],
            'session' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
            ],
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_time' => now()->toISOString(),
                'uptime' => $this->getUptime(),
            ]
        ];

        return response()->json($details);
    }

    /**
     * Get system uptime
     */
    private function getUptime()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return [
                'load_average' => $load,
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
            ];
        }

        return null;
    }
} 