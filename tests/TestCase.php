<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use LogicException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $app = parent::createApplication();

        $connection = (string) $app['config']->get('database.default');
        $configuration = $app['config']->get("database.connections.{$connection}", []);
        $driver = (string) ($configuration['driver'] ?? '');
        $database = $this->databaseName($configuration);

        if ($driver !== 'pgsql' || ! str_ends_with($database, '_test')) {
            throw new LogicException(
                "Tests require an isolated PostgreSQL database ending in _test; got {$driver}:{$database}."
            );
        }

        return $app;
    }

    /** @param array<string, mixed> $configuration */
    private function databaseName(array $configuration): string
    {
        $url = (string) ($configuration['url'] ?? '');

        if ($url !== '') {
            return ltrim((string) parse_url($url, PHP_URL_PATH), '/');
        }

        return (string) ($configuration['database'] ?? '');
    }
}
