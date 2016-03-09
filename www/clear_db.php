<?php

    # Errors
    ini_set('display_errors', 'on');
    error_reporting(E_ALL); # Записывать(показывать) все ошибки

    # AutoLoader
    chdir('../engine');
    include_once 'includes/AutoLoader.php';

    # delete DB
    $properties = new FileDB('properties');
    $properties->deleteDB();

    # Header
    header('Location: /', true, 307);