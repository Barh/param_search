<?php

    class FileDB
    {
        /**
         * Prefixes:
         * r - rights
         * p - path
         * n - names
         * m - message
         * c - columns
         * o - object
         *
         * Endings:
         * d - directory
         * f - file
         * db - data base
         * t - table
         * ni - next id
         * i - id
         * dt - data
         */
        private
            # rights
            $r_d = 0755, $r_f = 0644,
            # path
            $p_db = 'db', $p_t, $p_ni, $p_i,
            # names
            $n_t, $n_ni = 'next_id',
            # columns
            $c_items = array('name'=>''),
            $c_properties = array('name'=>''),
            $c_properties_values = array('name'=>''),
            $c_links = array();

        # values
        public $v_ni, $v_i, $v_dt, $m, $o;

        /**
         * Construct
         * @param string $table table name
         */
        public function __construct($table)
        {
            # set names and path
            $this->n_t = $table;
            $this->p_t = $this->p_db.'/'.$this->n_t;
            $this->p_ni = $this->p_t.'/'.$this->n_ni;

            # init object
            if ( class_exists(ucfirst($this->n_t), true) )
                $this->o = new $this->n_t;

            # init table
            $this->initTable();
            $this->initNextId();
        }

        /**
         * Init. Table
         */
        public function initTable()
        {
            # error make dir
            if (!is_dir($this->p_t) && !mkdir($this->p_t, $this->r_d, true))
                die('Error make dir: '. $this->p_t);

            return false;
        }

        /**
         * Init. Next id
         */
        public function initNextId()
        {
            # no exists file
            if ( !is_file($this->p_ni) )
            {
                # error init next id
                if ( !((file_put_contents($this->p_ni, $this->v_ni = 1, LOCK_EX) !== false) && chmod($this->p_ni, $this->r_f)) )
                    die('Error init next id: '.$this->p_ni);
            }
            # error get next id
            elseif ( !$this->v_ni = (int)file_get_contents($this->p_ni) )
                die('Error get next id: '.$this->p_ni);
        }

        /**
         * Init. Id
         * @param bool|int|array $id id
         */
        public function initId($id = false)
        {
            # set id
            $this->v_i = $id ? $this->getTreeId($id) : $this->v_ni;

            # path id
            $this->p_i = $this->p_t.'/'.$this->v_i;
        }

        /**
         * Set data
         * @param array $data data
         * @return bool
         */
        private function setData($data)
        {
            # additional handler
            if ( $this->o && method_exists( $this->o, 'setData' ) )
                if ( !$this->o->setData($this->v_i, $data) )
                    # error inserted
                    { $this->m = $this->o->m; return false; }

            $this->v_dt = array_intersect_key($data, $this->{'c_'.$this->n_t});
            return true;
        }

        /**
         * Insert
         * @param array $data data
         * @param bool|int|array $id id
         * @return bool
         */
        public function insert($data, $id = false)
        {
            # init id
            $this->initId($id);

            # set data
            if ( !$this->setData($data) )
                # error
                return false;

            # already inserted
            if ( is_dir($this->p_i) )
                // die ( $this->errorEnding('Error, already inserted') );
                { $this->m = $this->errorEnding('Error, already inserted'); return false; }

            # error insert
            if ( !is_writable($this->p_t) || !mkdir($this->p_i, $this->r_d, true))
                // die ($this->errorEnding('Error insert'));
                { $this->m = $this->errorEnding('Error insert'); return false; }

            # add keys and values
            foreach ($this->v_dt as $k=>$v)
            {
                # path to key
                $p_k = $this->p_i.'/'.$k;

                # error insert key
                if ( !( (file_put_contents($p_k, $v, LOCK_EX) !== false) && chmod($p_k, $this->r_f) ) )
                    // die ($this->errorEnding('Error insert key `'.$k.'` and value `'.$v.'`'));
                    { $this->m = $this->errorEnding('Error insert key `'.$k.'` and value `'.$v.'`'); return false; }
            }

            # increment next id
            if (!$id)
                $this->incrementNextId();

            # return inserted id
            $this->m = Language::get('filedb', 'success_insert');
            return $this->v_i;
        }

        /**
         * Increment next id
         */
        private function incrementNextId()
        {
            # error increment next id
            if ( !(file_put_contents($this->p_ni, ++$this->v_ni, LOCK_EX) !== false) )
                die('Error increment next id: '.$this->p_ni.' (to `'.$this->v_ni.'`)');
        }

        /**
         * Insert. Error ending
         * @param string $error_text error text
         * @return string error text with ending
         */
        private function errorEnding($error_text)
        {
            return $error_text.' (table: `'.$this->n_t.'`, id: `'.$this->v_i.'`)';
        }

        /**
         * Update
         * @param int $id id
         * @param array $data data
         * @return bool
         */
        public function update($id, $data)
        {
            # init id
            $this->initId($id);

            # set data
            if ( !$this->setData($data) )
                # error
                return false;

            # not exists
            if (!is_dir($this->p_i))
                // die ( $this->errorEnding('Error update, not exists') );
                { $this->m = $this->errorEnding('Error update, not exists'); return false; }

            # add keys and values
            foreach ($this->v_dt as $k=>$v)
            {
                # path to key
                $p_k = $this->p_i.'/'.$k;

                # error insert key
                if ( !( (file_put_contents($p_k, $v, LOCK_EX) !== false) && chmod($p_k, $this->r_f) ) )
                    // die ($this->errorEnding('Error update key `'.$k.'` and value `'.$v.'`'));
                    { $this->m = $this->errorEnding('Error update key `'.$k.'` and value `'.$v.'`'); return false; }
            }

            $this->m = Language::get('filedb', 'success_update');
            return true;
        }

        /**
         * Delete
         * @param int $id id
         */
        public function delete($id)
        {
            # init id
            $this->initId($id);

            # not exists
            if (!is_dir($this->p_i))
                // die ( $this->errorEnding('Error delete, not exists') );
                { $this->m = $this->errorEnding('Error delete, not exists'); return false; }

            # delete tree
            if ($this->deleteTree($this->p_i))
                { $this->m = Language::get('filedb', 'success_delete'); return true; }
            else
                return false;
        }

        /**
         * Delete tree
         * @param string $dir directory
         * @return bool
         */
        private function deleteTree($dir)
        {
            # exclude "dot" files
            $files = array_diff(scandir($dir), array('.', '..'));

            # delete files recursive
            foreach ($files as $file)
            {
                # is dir
                if ( is_dir("$dir/$file") )
                    $this->deleteTree("$dir/$file");
                # is file
                else
                    # error remove file
                    if ( !unlink("$dir/$file") )
                        { $this->m = $this->errorEnding("Error delete unlink $dir/$file"); return false; }
            }

            # error remove dir
            if ( !rmdir($dir) )
                { $this->m = $this->errorEnding("Error remove dir $dir/$file"); return false; }

            return true;
        }

        /**
         * Delete DB
         * @return bool
         */
        public function deleteDB()
        {
            return $this->deleteTree($this->p_db);
        }

        /**
         * Select
         * @param int|array $id id
         * @param bool|string $flag flag
         * @return array
         */
        public function select($id, $flag = false)
        {
            # id array
            if ( is_array($id) )
            {
                # get data
                foreach ($id as $v)
                    if ( ($temp = $this->select($v, $flag)) !== false )
                        $data[$this->v_i] = $temp;

                # key sort data
                ksort($data);

                # return data
                return $data;
            }
            # id integer
            else
            {
                # init id
                $this->initId($id);

                # not exists
                if (!is_dir($this->p_i))
                    // die ( $this->errorEnding('Error select, not exists') );
                    { $this->m = $this->errorEnding('Error select, not exists'); return false; }

                # exclude "dot" files
                $files = array_diff(scandir($this->p_i), array('.', '..'));

                # get files recursive
                foreach ($files as $v)
                    # error get value from key
                    if ( ($data[$v] = file_get_contents($this->p_i.'/'.$v)) === false )
                        // die($this->errorEnding('Error get value from key `'.$v.'`'));
                        { $this->m = $this->errorEnding('Error get value from key `'.$v.'`'); return false; }

                # get correct and fill columns data
                $data = isset($data) ? $data : array();
                $data = array_merge( array_intersect_key($data, $this->{'c_'.$this->n_t}), array_diff_key($this->{'c_'.$this->n_t}, $data));

                # additional handler
                if ( $this->o && method_exists( $this->o, 'select' ) )
                    if ( !$this->o->select($data, $this->v_i, $flag) )
                        # error selected
                        { $this->m = $this->o->m; return false; }

                # return data
                $this->m = Language::get('filedb', 'success_select');
                return $data;
            }
        }

        /**
         * Get tree id
         * @param int|array $id
         * @return int|string
         */
        private function getTreeId($id)
        {
            # tree id
            if (is_array($id))
            {
                $p = '';
                foreach ($id as $v)
                    $p .= (int)$v.'/';
                return substr($p, 0, -1);
            }
            # id
            else
                return (int)$id;
        }

        /**
         * Select all
         * @param bool|integer|array $id id
         * @param int $level_b level before depth for path
         * @param int $level_a level after depth for path
         * @param int $level_r level return from end
         * @param bool|array $filter_id filter id on level depth
         * @param bool|string $flag flag
         * @return array|bool
         */
        public function selectAll($id = false, $level_b = 0, $level_a = 1, $level_r = 1, $filter_id = false, $flag = false )
        {
            # get ids
            if ($ids = $this->selectAllId($id, $level_b, $level_a, $level_r, $filter_id))
                # get data
                if ( ($ids = $this->select($ids, $flag)) === false)
                    # error
                    return false;

            return $ids;
        }

        /**
         * Select all id
         * @param bool|integer|array $id id
         * @param int $level_b level before depth for path
         * @param int $level_a level after depth for path
         * @param int $level_r level return from end
         * @param bool|array $filter_id filter id on level depth
         * @return array
         */
        public function selectAllId($id = false, $level_b = 0, $level_a = 1, $level_r = 1, $filter_id = false )
        {
            # path to id
            $path = $id ? '/'.$this->getTreeId($id) : '';

            # get level depth (before and after)
            foreach (array('b', 'a') as $v)
                for ($i = 1, ${'template_'.$v} = ''; $i <= ${'level_'.$v}; $i++)
                    ${'template_'.$v} .= '/*';

            # get path
            if ( $path = glob($this->p_t.$template_b.$path.$template_a) )
            {
                # get only id
                foreach ($path as $v)
                {
                    # exclude other files (ex. `next_id`, not filter id)
                    # only filter ids
                    $id = basename($v);
                    if ( !in_array($id, array($this->n_ni)) && (!$filter_id || in_array($id, $filter_id)) )
                    {
                        # level returned
                        if ($level_r == 1)
                            $ids[(int)$id] = (int)$id;
                        # custom on level depth
                        else
                        {
                            $id = explode('/', $v);
                            $id = $id[count($id) - $level_r];
                            $ids[(int)$id] = (int)$id;
                        }
                    }
                }
            }

            return isset($ids) ? $ids : array();
        }

        /**
         * Select all names
         * @param bool|integer|array $id id
         * @param int $level_b level before depth for path
         * @param int $level_a level after depth for path
         * @param int $level_r level return from end
         * @param bool|array $filter_id filter id on level depth
         * @return array|bool
         */
        public function selectAllNames($id = false, $level_b = 0, $level_a = 1, $level_r = 1, $filter_id = false, $flag = false)
        {
            # error
            if ( ($temp = $this->selectAll($id, $level_b, $level_a, $level_r, $filter_id, $flag)) === false )
                return false;

            # handler name
            $data = array();
            foreach ($temp as $k=>$v)
                $data[$k] = $v['name'];

            return $data;
        }

        /**
         * Get columns
         * @return array
         */
        public function getColumns()
        {
            return $this->{'c_'.$this->n_t};
        }

        /**
         * Is?
         * @param integer|array string $id id
         * @return bool
         */
        public function is($id)
        {
            # init id
            $this->initId($id);

            # is
            return is_dir($this->p_i);
        }
    }