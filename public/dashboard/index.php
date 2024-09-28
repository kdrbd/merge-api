<?php
    
    use Core\Session;

    require __DIR__.'/../../vendor/autoload.php';

    Session::init();
    Session::checkSession();

    echo "Welcome To Dash Board...!!";

?>