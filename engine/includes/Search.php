<?php

    class Search
    {
        public $m;
        private $o_i, $o_l;

        /**
         * Init object
         * @param string $ending ending
         */
        private function initObject($ending)
        {
            # ending
            switch ($ending)
            {
                # items
                case 'i':
                    if (!$this->o_i)
                        $this->o_i = new FileDB('items');
                    break;
                # properties
                case 'p':
                    if (!$this->o_p)
                        $this->o_p = new FileDB('properties');
                    break;
                # properties_values
                case 'pv':
                    if (!$this->o_pv)
                        $this->o_pv = new FileDB('properties_values');
                    break;
                # links
                case 'l':
                    if (!$this->o_l)
                        $this->o_l = new FileDB('links');
                    break;
            }
        }

        /**
         * Search items
         * @param array $properties_id properties values id
         * @return bool
         */
        public function items($properties_id)
        {
            # links
            $this->initObject('l');
            foreach ($properties_id as $p_id=>$p_v)
            {
                foreach ($p_v as $pv_id)
                {
                    # select items ids
                    if ( ($result = $items_ids[] = $this->o_l->selectAllId($p_id, 1, 1, 3, array($pv_id))) === false )
                        # error
                        { $this->m = $this->o_l->m; return false; }
                }
            }

            # filter items (and)
            if ($items_ids = (count($items_ids) > 1) ? call_user_func_array('array_intersect_key', $items_ids) : $items_ids[0])
            {
                # items
                $this->initObject('i');
                foreach ($items_ids as $v)
                    if ( ($result = $data[$v] = $this->o_i->select($v) ) === false)
                        # error
                        { $this->m = $this->o_i->m; return false; }

                return $data;
            }

            return true;
        }

        /**
         * Search properties
         * @param $items
         * @return array
         */
        public function properties($items)
        {
            # properties available
            foreach ($items as $i)
                foreach ($i['properties'] as $p_id=>$p_v)
                    $properties[$p_id][$p_v['value']] = $p_v['value'];

            return $properties;
        }
    }