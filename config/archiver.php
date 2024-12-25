<?php

/**
 * Photo Archiver config.
 */
return [
    /**
     * Default strategy.
     */
    'default-strategy' => 'per-month-per-day',

    /**
     * Available strategy.
     */
    'strategies' => [
        'per-month-per-day' => new \App\Strategies\PerMonthAndDayStrategy,
        'per-month' => new \App\Strategies\PerMonthStrategy,
        'per-day' => new \App\Strategies\PerDayStrategy,
    ],
];
