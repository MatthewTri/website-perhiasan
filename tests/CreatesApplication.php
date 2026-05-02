<?php

namespace Tests;

use Illuminate\Foundation\Application;

use RuntimeException;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

        // Safety guard: prevent accidentally running tests against a real DB.
        // Disable by setting TEST_DB_GUARD=0 in phpunit.xml.
        $guardEnabled = (string) ($_ENV['TEST_DB_GUARD'] ?? '1') !== '0';
        if ($guardEnabled && $app->environment('testing')) {
            $defaultConnection = $app['config']->get('database.default');
            $driver = $app['config']->get("database.connections.{$defaultConnection}.driver");
            $database = $app['config']->get("database.connections.{$defaultConnection}.database");

            if ($driver !== 'sqlite' || $database !== ':memory:') {
                throw new RuntimeException(
                    "Unsafe test DB configuration detected. Expected sqlite :memory:, got {$driver} ({$database}). " .
                    "Fix phpunit.xml DB_CONNECTION/DB_DATABASE or set TEST_DB_GUARD=0 to bypass."
                );
            }
        }

        return $app;
    }
}
