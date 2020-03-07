<?php

use Base\Controller;
use Base\View;

require_once __DIR__ . '/../vendor/autoload.php';

$parts = explode('/', $_SERVER['REQUEST_URI']);
$controllerName = "\App\Controller\\" . ucfirst($parts[1] ?? 'index');
$actionName = ucfirst($parts[2] ?? 'Index');

/** @var Controller $controller */
$controller = new $controllerName();

$view = (new View())->setTemplatePath(__DIR__ . '/../app/view');
$controller->setView($view);

$controller->$actionName();