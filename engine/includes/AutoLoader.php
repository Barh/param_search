<?php

    class AutoLoader
    {
        public static function init()
        {
            // Включаем AutoLoader ссылаясь на метод
            spl_autoload_register( __CLASS__ .'::autoload' );
        }

        /**
         * AutoLoader Classes
         * @param $className
         */
        private static function autoload($className)
        {
            # Подключаем файл с классом, если такой найден
            # сначала ищем в главной папке includes
            if ( file_exists( ($classPath = __DIR__.'/'.$className.'.php') ) )
                include_once( $classPath );

            # Запускаем метод init, если он существует
            if ( method_exists($className, 'init' ) )
                $className::init();
        }
    }

    AutoLoader::init();