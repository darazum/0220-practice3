<?php
namespace Base;

abstract class Controller
{
    /** @var View */
    protected $view;

    public function setView(View $view)
    {
        $this->view = $view;
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url);
        exit;
    }
}