<?php
// Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if any user is logged in.
 */
function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Return current user array or null.
 */
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Check if current user is admin.
 * Expects $_SESSION['user']['role'] === 'admin'
 */
function is_admin(): bool
{
    return is_logged_in() && !empty($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Require login or redirect to login page.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        $_SESSION['flash']['danger'][] = 'Please sign in to continue.';
        header('Location: /login.php');
        exit;
    }
}

/**
 * Require admin role or redirect.
 */
function require_admin(): void
{
    if (!is_admin()) {
        $_SESSION['flash']['danger'][] = 'You are not authorized to access that page.';
        header('Location: /events.php');
        exit;
    }
}
