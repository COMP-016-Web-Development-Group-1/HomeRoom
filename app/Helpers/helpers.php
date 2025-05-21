<?php

if (!function_exists('generate_code')) {
    function generate_code(): string
    {
        $part1 = strtoupper(Str::random(3));
        $part2 = strtoupper(Str::random(3));

        return "{$part1}-{$part2}";
    }
}
