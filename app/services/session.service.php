<?php

function setSession($key, $value)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION[$key] = $value;
}

function getSession($key, $default = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION[$key] ?? $default;
}

function hasSession($key)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION[$key]);
}

function removeSession($key)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION[$key]);
}

function clearSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
}