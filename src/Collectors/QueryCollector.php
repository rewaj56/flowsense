<?php

namespace Rewaj56\Flowsense\Collectors;

class QueryCollector
{
    protected static array $queries = [];
    protected static float $totalTime = 0.0;

    public static function listen(): void
    {
        static::$queries = [];
        static::$totalTime = 0.0;
    }

    public static function add(array $query): void
    {
        static::$queries[] = $query;
        static::$totalTime += $query['time'];
    }

    public static function collect(): array
    {
        return [
            'count' => count(static::$queries),
            'total_time' => number_format(static::$totalTime, 4),
            'queries' => static::$queries,
        ];
    }
}
