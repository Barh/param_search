<?php
    # page, format
    $data['page'] = isset($_GET['page']) ? $_GET['page'] : 'properties_values';
    $data['format'] = (isset($_GET['format']) && $_GET['format'] == 'json') ? 'json' : '';

    # choose page
    switch ($data['page'])
    {
        # items properties
        case 'items':
        case 'properties':
            # incorrect type
            $data['type'] = !isset($_GET['type']) ? 'select' : $_GET['type'];
            if ( isset($_GET['type']) && !in_array($data['type'], array('insert', 'update', 'delete')) )
                die('Error: incorrect parameter `type`');

            # incorrect id
            if ($data['type'] != 'insert')
                if (!isset($_GET['id']) || !is_numeric($data['id'] = (int)$_GET['id']))
                    // die('Error: incorrect parameter `id`');
                    $data['type'] = 'select_list';

            # get fileDB object
            $fileDB = new FileDB($data['page']);

            # type
            switch ($data['type'])
            {
                # select, delete
                case 'select':
                case 'select_list':
                case 'delete':
                    # operation
                    if ($data['type'] == 'select_list')
                        $result = $fileDB->selectAll();
                    else
                        if ($data['page'] == 'properties')
                            $result = $fileDB->{$data['type']}($data['id'], 'pv');
                        else
                            $result = $fileDB->{$data['type']}($data['id']);

                    # select
                    if (in_array($data['type'], array('select', 'select_list')) && $result)
                        $response['data'] = $result;

                    break;
                # insert, update
                case 'insert':
                case 'update':

                    # if insert need view as select
                    if ($data['type'] == 'insert' && !isset($_REQUEST['data']))
                        { $response['data'] = $fileDB->getColumns(); break; }

                    # get data
                    $data['data'] = !isset($_REQUEST['data']) ? array() : $_REQUEST['data'];

                    # operation
                    if ($data['type'] == 'insert')
                    {
                        # insert
                        if ( $result = $fileDB->{$data['type']}($data['data']) )
                            $response['id'] = $result;
                    }
                    # update
                    else
                        $result = $fileDB->{$data['type']}($data['id'], $data['data']);

                    break;
            }
            break;
        # properties values, search
        case 'properties_values':
        case 'search':
            # select properties values
            $fileDB = new Properties;
            if ( ($result = $fileDB->selectValues()) !== false )
            {
                # properties values
                if ($data['page'] == 'properties_values')
                    $data['pv'] = $response['data'] = $result;
                # search
                else
                {
                    # properties values
                    $data['pv'] = $result;

                    # properties_id
                    if ($data['properties_id'] = isset($_GET['properties_id']) ? $_GET['properties_id'] : array())
                    {
                        # search items
                        $fileDB = new Search();
                        if ( ($result = $fileDB->items($data['properties_id'])) === false )
                            break;

                        # available properties
                        if (is_array($result))
                            $response['properties'] = $fileDB->properties($response['data'] = $result);
                    }
                }
            }

            break;
        # incorrect parameters
        default:
            die('Error: incorrect parameter `page`');
            break;
    }

    # get additional items data
    if ( in_array($data['page'], array('items', 'search')) && isset($response['data']) )
    {
        # get all properties names
        $db_p = new FileDB('properties');
        $data['properties'] = $db_p->selectAll();
    }

    # json response format
    if ($data['format'] == 'json')
    {
        # header json
        header('Content-Type: application/json');

        # result and message
        $response['result']  = $result !== false;
        $response['message'] = $fileDB->m;

        # json encode
        ob_get_clean();
        echo json_encode($response);
        exit;
    }