<?php

    # Errors
    ini_set('display_errors', 'on');
    error_reporting(E_ALL); # Записывать(показывать) все ошибки

    # AutoLoader
    chdir('../engine');
    include_once 'includes/AutoLoader.php';


    # properties data
    $properties_data = array(
        array('name' => 'Операционная система'),
        array('name' => 'Производитель'),
        array('name' => 'Цвет'),
        array('name' => 'Вес'),
        array('name' => 'Экран'),
        array('name' => 'Объём памяти'),
    );

    # items data
    $items_data = array(
        array(
            'name' => 'Apple iPhone 5 16Gb',
            'properties' => array(
                1 => 'iOS6',
                2 => 'Apple',
                3 => 'Чёрный',
                4 => '112г',
                5 => '4"',
                6 => '16Gb',
            ),
        ),
        array(
            'name' => 'Apple iPhone 4S 16Gb',
            'properties' => array(
                1 => 'iOS5',
                2 => 'Apple',
                3 => 'Красный',
                4 => '140г',
                5 => '3.5"',
                6 => '16Gb',
            ),
        ),
        array(
            'name' => 'Apple iPhone 5 32Gb',
            'properties' => array(
                1 => 'iOS6',
                2 => 'Apple',
                3 => 'Синий',
                4 => '112г',
                5 => '4"',
                6 => '32Gb',
            ),
        ),
        array(
            'name' => 'Apple iPhone 5 64Gb',
            'properties' => array(
                1 => 'iOS6',
                2 => 'Apple',
                3 => 'Чёрный',
                4 => '112г',
                5 => '4"',
                6 => '64Gb',
            ),
        ),
        array(
            'name' => 'Samsung Galaxy S III GT-I9300 16Gb',
            'properties' => array(
                1 => 'Android 4.0',
                2 => 'Samsung',
                3 => 'Белый',
                4 => '133г',
                5 => '4.8"',
                6 => '16Gb',
            ),
        ),
        array(
            'name' => 'Samsung Galaxy S4 16Gb GT-I9500',
            'properties' => array(
                1 => 'Android 4.2',
                2 => 'Samsung',
                3 => 'Синий',
                4 => '130г',
                5 => '5"',
                6 => '16Gb',
            ),
        ),
        array(
            'name' => 'Samsung Galaxy Note II GT-N7100 16Gb',
            'properties' => array(
                1 => 'Android 4.1',
                2 => 'Samsung',
                3 => 'Красный',
                4 => '180г',
                5 => '5.55"',
                6 => '16Gb',
            ),
        ),
        array(
            'name' => 'Sony Xperia Z',
            'properties' => array(
                1 => 'Android 4.1',
                2 => 'Sony',
                3 => 'Чёрный',
                4 => '146г',
                5 => '5"',
                6 => '16Gb',
            ),
        ),
        array(
            'name' => 'HTC One 32Gb',
            'properties' => array(
                1 => 'Android 4.1',
                2 => 'HTC',
                3 => 'Серый',
                4 => '143г',
                5 => '4.7"',
                6 => '32Gb',
            ),
        ),
        array(
            'name' => 'Nokia Lumia 920',
            'properties' => array(
                1 => 'Windows Phone 8',
                2 => 'Nokia',
                3 => 'Красный',
                4 => '185г',
                5 => '4.5"',
                6 => '32Gb',
            ),
        ),
    );

    # delete DB
    $properties = new FileDB('properties');
    $properties->deleteDB();

    # add properties.
    $properties->__construct('properties');
    foreach ($properties_data as $v)
        $properties->insert($v);

    # add items
    $items = new FileDB('items');
    foreach ($items_data as $v)
        $items->insert($v);

    # Header
    header('Location: /', true, 307);