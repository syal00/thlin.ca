<?php

use App\Services\ThlinContentImporter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('thlin:import-content', function () {
    $importer = app(ThlinContentImporter::class);

    $this->info('Importing content from https://thlin.ca …');
    $importer->import(function (string $message): void {
        $this->line($message);
    });
    $this->info('Done.');
})->purpose('Import pages, news, careers, board, and portfolio from thlin.ca');
