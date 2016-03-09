<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title><?php echo Language::get('main', 'title'); ?></title>
    <link href="css/main.css" type="text/css" rel="stylesheet">
    <!-- jQuery -->
    <!--[if lt IE 9]>
    <script src="js/jquery-1.12.1.min.js" type="text/javascript"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script src="js/jquery-2.2.1.min.js" type="text/javascript"></script>
    <!--<![endif]-->
    <script src="js/main.js" type="text/javascript"></script>
</head>
<body>
    <!-- Main container -->
    <div class="ps-main-container">

        <?php if ( $data['page'] && !in_array($data['page'], array('properties_values', 'search')) ):?>
            <!-- Back -->
            <div class="ps-link ps-link-back">
                <a href="."><?php echo Language::get('main', 'back'); ?></a>
            </div>
            <?php
            switch ($data['type'])
            {
                case 'insert':
                case 'select':
                case 'update':
                case 'delete':
                    include_once 'point.php';
                    break;
                case 'select_list':
                    include_once 'point_list.php';
                    break;
            };
        else: ?>

        <!-- Menu -->
        <div class="ps-menu">
            <!-- Item insert -->
            <div class="ps-link">
                <a href="?page=items&type=insert"><?php echo Language::get('items', 'insert'); ?></a>
            </div>

            <!-- Items -->
            <div class="ps-link">
                <a href="?page=items"><?php echo Language::get('items', 'select_all'); ?></a>
            </div>

            <!-- Separate -->
            <div class="ps-link-separate"></div>

            <!-- Property insert -->
            <div class="ps-link">
                <a href="?page=properties&type=insert"><?php echo Language::get('properties', 'insert'); ?></a>
            </div>

            <!-- Properties -->
            <div class="ps-link">
                <a href="?page=properties"><?php echo Language::get('properties', 'select_all'); ?></a>
            </div>
        </div>

        <?php
            # search templates
            include_once 'search_properties.php';
            if ($data['page'] == 'search')
                include_once 'search_items.php';
        endif; ?>
    </div>
</body>
</html>