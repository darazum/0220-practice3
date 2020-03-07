<?php
namespace App\Controller;

class Index
{
    public function index()
    {
        echo '<pre>' . print_r($_SERVER, 1);
    }
}