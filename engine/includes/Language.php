<?php

    class Language
    {
        /**
         * Current language
         * @var
         */
        public static $lang;

        /**
         * Array with strings
         * @var
         */
        private static $data;

        /**
         * Init
         */
        public static function init()
        {
            self::$lang = 'ru';
        }

        /**
         * Get string
         * @param string $file file with language array
         * @param string $key key string
         * @param bool $array return array?
         * @return string
         */
        public static function get($file, $key, $array = false)
        {
            # no isset language data array
            if (!isset(self::$data[$file]))
            {
                # get language array
                if (file_exists('languages/'.self::$lang.'/'.$file.'.php'))
                    include_once 'languages/'.self::$lang.'/'.$file.'.php';

                # save in class property
                self::$data[$file] = isset($lang) ? $lang : array();
            }

            # return result
            if (!$array)
                return isset(self::$data[$file][$key]) ? self::$data[$file][$key] : '';
            else
                return isset(self::$data[$file]) ? self::$data[$file] : false;
        }
    }