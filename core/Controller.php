<?php

namespace Core;

abstract class Controller
{
    /**
     * Render a view template with data.
     *
     * @param string $template View name (without .php)
     * @param array $data Data for the view
     */
    protected function view(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../app/Views/' . $template . '.php';
    }

    /**
     * Redirect to a URL.
     *
     * @param string $url
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}