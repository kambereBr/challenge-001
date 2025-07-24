<?php

namespace Core;

abstract class Controller
{
    protected $currentUser;

    public function __construct()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->currentUser = \App\Models\User::find($_SESSION['user_id']);
    }

    /**
     * Authorize access for given user roles.
     *
     * @param array $roles
     */
    protected function authorize(array $roles): void
    {
        if (! in_array($this->currentUser->role, $roles)) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }
    }

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