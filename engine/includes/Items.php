<?php

    class Items
    {
        /**
         * Prefixes:
         * o - object
         * m - message
         *
         * Endings:
         * p - properties
         * pv - properties values
         * l - links
         */
        private $o_p, $o_pv, $o_l;
        public $m;

        /**
         * Init object
         * @param string $ending ending
         */
        private function initObject($ending)
        {
            # ending
            switch ($ending)
            {
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
         * Set Data
         * @param int|string $item_id item id
         * @param array $data data
         * @return bool
         */
        public function setData($item_id, $data)
        {
            # init `links` object and if is delete links for item id
            $this->initObject('l');
            if ( $this->o_l->is($item_id) )
                $this->o_l->delete($item_id);

            # init `properties values` and select all names
            $this->initObject('pv');
            $pv = $this->o_pv->selectAllNames();

            # is properties
            if ( !empty($data['properties']) )
            {
                # clear empty strings
                $data['properties'] = array_filter(
                    $data['properties'],
                    function($value) { return !empty($value) || $value === 0; }
                );

                # is properties
                if ( $data['properties'] )
                {
                    # insert properties
                    foreach ($data['properties'] as $prop_id=>$v)
                    {
                        # is new value
                        if ( ($pv_id = array_search($v, $pv)) === false )
                            # insert
                            if ( ($pv_id = $this->o_pv->insert(array('name' => $v))) === false )
                                # error
                                { $this->m = $this->o_pv->m; return false; }

                        # no is link
                        if ( !$this->o_l->is( array($item_id, $prop_id, $pv_id), true) )
                            # insert links
                            if ( ($this->o_l->insert(array(), array($item_id, $prop_id, $pv_id), true) === false) )
                                # error
                                { $this->m = $this->o_l->m; return false; }
                    }
                }
            }

            # get current and used properties values ids
            $pv_ids_current = $this->o_pv->selectAllId();
            $pv_ids_used = $this->o_l->selectAllId(false, 0, 3);

            # get free properties values
            if ($pv_delete = array_diff_key($pv_ids_current, $pv_ids_used))
                # delete
                foreach ($pv_delete as $v)
                    # delete (clear)
                    if (!$this->o_pv->delete($v))
                        { $this->m = $this->o_pv->m; return false; }

            return true;
        }

        /**
         * Select
         * @param array $data data
         * @param int $item_id item id
         * @param bool|string $flag flag
         * @return bool
         */
        public function select(&$data, $item_id, $flag = false)
        {
            # init `properties`
            $this->initObject('p');
            $this->initObject('pv');
            $this->initObject('l');

            # get properties id by item id
            if ( ($props_id = $this->o_l->selectAllId($item_id)) === false)
                # error
                { $this->m = $this->o_l->m; return false; }

            foreach ($props_id as $v)
            {
                # get property value id
                if ( ($pv_id = $this->o_l->selectAllId(array($item_id, $v), 0, 1)) === false )
                    # error
                    { $this->m = $this->o_l->m; return false; }
                $data['properties'][$v]['value'] = $pv_id = array_shift($pv_id);

                # if need select property data
                if (!isset($pv_ids[$pv_id]))
                {
                    # select property data (ex. `name`)
                    if ( ($pv_ids[$pv_id] = $this->o_pv->select($pv_id)) === false )
                        # error
                        { $this->m = $this->o_pv->m; return false; }
                }
                # set in properties
                $data['properties'][$v] = array_merge($data['properties'][$v], $pv_ids[$pv_id]);
            }

            return true;
        }
    }