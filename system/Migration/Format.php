<?php

namespace Asgard\system\Migration;

class Format
{
    public static function formatSuccessMessage(string $message): string
    {
        return "\e[32m$message\e[0m";
    }

    public static function formatWarningMessage(string $message): string
    {
        return "\e[33m$message\e[0m";
    }
}