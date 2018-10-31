<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/4/25
     * Time: 16:40
     */

    namespace Addcn\Model\ES\Queries\Words;


    class Bool extends Common
    {

        /**
         * @see Bool
         * @var object
         */
        protected $bool;


        /**
         * @param int $id
         * @return \Addcn\Model\ES\Queries\Bool|mixed|object
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

                $this->bool[$id] = new \Addcn\Model\ES\Queries\Bool($this->caller);
                $this->_setCondition($this->bool[$id]);

                return $this->bool[$id];

            } else {
                if (!$this->bool) {
                    $this->bool = new \Addcn\Model\ES\Queries\Bool($this->caller);
                    $this->_setCondition($this->bool);
                }

                return $this->bool;
            }

        }
    }