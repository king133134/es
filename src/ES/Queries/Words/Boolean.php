<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/4/25
     * Time: 16:40
     */

    namespace ElasticSearch\ES\Queries\Words;


    class Boolean extends Common
    {

        /**
         * @see Bool
         * @var object
         */
        protected $bool;


        /**
         * @param int $id
         * @return \ElasticSearch\ES\Queries\Boolean|mixed|object
         */
        public function bool($id = 0)
        {

            if ($this->conditionMultiple) {

                if (!is_array($this->bool)) {
                    $this->bool = array();
                }
                if ($this->bool[$id]) {
                    return $this->bool[$id];
                }

                $this->bool[$id] = new \ElasticSearch\ES\Queries\Boolean($this->caller);
                $this->_setCondition($this->bool[$id]);

                return $this->bool[$id];

            } else {
                if (!$this->bool) {
                    $this->bool = new \ElasticSearch\ES\Queries\Boolean($this->caller);
                    $this->_setCondition($this->bool);
                }

                return $this->bool;
            }

        }
    }