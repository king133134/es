<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 17:59
     */

    namespace Addcn\Model\ES\Queries\Words;


    use Addcn\Model\ES\Caller;

    class Base extends \Addcn\Model\ES\Queries\Base
    {

        protected $conditionCollection;
        protected $conditionMultiple = true;//條件多選還是單選
        protected $keyWord;


        protected function _getConditionCollection()
        {
            return $this->conditionCollection;
        }


        /**
         * @return array
         */
        public function getConditions()
        {


            $conditions = $this->_getConditionCollection();
            if (empty($conditions)) {
                return array();
            }

            $result = array();


            if (is_array($conditions)) {

                foreach ($conditions as $key => $condition) {
                    $res = $this->_getConditionArray($condition);
                    if (empty($res)) {
                        continue;
                    }
                    $result[$key] = $res;
                }

            } else {

                $result = $this->_getConditionArray($conditions);

            }

            return array($this->keyWord => $result);

        }

        protected function _getConditionArray($condition)
        {

            if (is_object($condition) && $condition instanceof \Addcn\Model\ES\Queries\Base) {
                return $condition->getConditions();
            } else {
                return $condition;
            }
        }

        protected function _setCondition($condition)
        {

            if (empty($condition)) {
                return $this;
            }

            if ($this->conditionMultiple) {
                if (!is_array($this->conditionCollection)){
                    $this->conditionCollection = array();
                }
                $this->conditionCollection[] = $condition;
            } else {
                $this->conditionCollection = $condition;
            }

            return $this;

        }


    }