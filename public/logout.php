<?php
    use Core\Session;

    require __DIR__.'/../vendor/autoload.php';

    Session::init();
    Session::destroy();
?>