<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;

const BASE_PATH = __DIR__ . '/..';

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Asia/Kolkata');

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = BASE_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($path)) {
        require $path;
    }
});

$GLOBALS['config'] = require BASE_PATH . '/app/Config/config.php';

function config(string $key, mixed $default = null): mixed
{
    $segments = explode('.', $key);
    $value = $GLOBALS['config'] ?? [];

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function request_path(): string
{
    $route = trim((string) ($_GET['route'] ?? ''), '/');

    if ($route !== '') {
        return $route;
    }

    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $basePath = rtrim(str_replace('/index.php', '', $scriptName), '/');

    if ($basePath !== '' && str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath)) ?: '/';
    }

    return trim($uri, '/');
}

function url(string $path = ''): string
{
    $baseUrl = rtrim((string) config('app.base_url', ''), '/');
    $path = trim($path, '/');

    if ($path === '') {
        return $baseUrl === '' ? '/' : $baseUrl . '/';
    }

    $route = str_replace('%2F', '/', rawurlencode($path));

    return ($baseUrl === '' ? '' : $baseUrl) . '/index.php?route=' . $route;
}

function redirect(string $path = ''): never
{
    header('Location: ' . url($path));
    exit;
}

function asset(string $path): string
{
    return url(trim($path, '/'));
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_field(): string
{
    $token = Csrf::token();

    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

function old(string $key, string $default = ''): string
{
    return (string) (Session::get('old.' . $key, $default));
}

function selected(string $value, string $expected): string
{
    return $value === $expected ? 'selected' : '';
}

function checked(bool $condition): string
{
    return $condition ? 'checked' : '';
}
