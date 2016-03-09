<?php

    /**
     * Class Parameters
     */
    class Parameters
    {
        /**
         * Фильтруем данные массива
         * @param array $data Массив с корректными ключами и значениями
         * @param array $allow Массив с обязательными значениями
         * @param bool $accuracy Конечный массив должен в точности соответствовать по количеству элементов фильтру?
         * @return array|bool
         */
        public static function filter($data, $allow, $accuracy = false)
        {
            # Фильтруем данные
            foreach ($allow as $v)
                if ( array_key_exists($v, $data) )
                    $array[$v] = $data[$v];

            # Возвращаем (с жёсткой фильтрацией или нет)
            return isset($array) ? (!$accuracy ? $array : (count($array) == count($allow) ? $array : false)) : false;
        }
    }