<?php

namespace Rewaj56\Flowsense\Collectors;

class ViewCollector
{
    protected static array $views = [];

    public static function add(array $data)
    {
        static::$views[] = $data;
    }

    public static function getAll(): array
    {
        return static::$views;
    }
}

