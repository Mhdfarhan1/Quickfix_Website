<?php

use Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy;

return [

    'backup' => [

        'name' => 'backups',

        'source' => [

            'files' => [
                'include' => [
                    base_path(),
                ],

                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    storage_path('app/backup-temp'),
                    storage_path('app/private'),
                ],

                'follow_links' => false,
                'ignore_unreadable_directories' => false,

                // WAJIB untuk Spatie Backup v9.x
                'relative_path' => base_path(),
            ],

            'databases' => [
                env('DB_CONNECTION', 'mysql'),
            ],
        ],

        'database_dump_compressor' => null,
        'database_dump_file_extension' => 'sql',
    ],

    'destination' => [
        'filesystem' => 'local',
        'path' => 'backups/db',
    ],

    'temporary_directory' => storage_path('app/backup-temp'),

    'middleware' => [
        'backup' => [],
        'cleanup' => [],
    ],

    'disks' => [
        'backups' => [
            'driver' => 'local',
            'root' => storage_path('app/backups/db'),
        ],
    ],

    'cleanup' => [
        'strategy' => DefaultStrategy::class,
        'default_strategy' => [
            'keep_all_backups_for_days' => 7,
            'keep_daily_backups_for_days' => 16,
            'keep_weekly_backups_for_weeks' => 8,
            'keep_monthly_backups_for_months' => 4,
            'keep_yearly_backups_for_years' => 2,
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],
    ],
];
