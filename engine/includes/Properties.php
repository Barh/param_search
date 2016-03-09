<?php

    class Properties
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
         * Select
         * @param array $data data
         * @param int $property_id property id
         * @param bool|string $flag flag
         * @return bool
         */
        public function select(&$data, $property_id, $flag = false)
        {
            # property values
            if ($flag == 'pv')
            {
                # init `links` and `properties values`
                $this->initObject('l');
                $this->initObject('pv');

                # get all properties values keys by property id
                if ( ($pv_keys = $this->o_l->selectAllId($property_id, 1, 1)) === false )
                    # error
                    { $this->m = $this->o_l->m; return false; }

                # each keys
                foreach ($pv_keys as $pv_id)
                {
                    # select property data (ex. `name`)
                    if ( ($pv_data = $this->o_pv->select($pv_id)) === false )
                        # error
                        { $this->m = $this->o_pv->m; return false; }

                    # set in ids
                    $data['values'][$pv_id] = $pv_data['name'];
                }
            }

            return true;
        }

        /**
         * Select properties values
         * @return array|bool
         */
        public function selectValues()
        {
            $this->initObject('p');
            if ( ($data = $this->o_p->selectAll(false, 0, 1, 1, false, 'pv')) === false )
                # error
                { $this->m = $this->o_pv->m; return false; }

            return $data;
        }
    }