<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 15:38
     */

    namespace ElasticSearch\ES;

    class Search extends Caller
    {

        protected $fields = array();

        protected $scriptFields = array();

        public function fields(array $fields = array())
        {

            $this->fields = $fields;

            return $this;

        }

        public function scriptFields()
        {

        }


        public function get($data_type = 'json')
        {

            $request = $this->_getRequest();

            $json = $this->getCurl()->setMethod($request['method'])->send($request['url'], json_encode($request['params']));

            if ($data_type == 'array') {

                return json_decode($json, true);

            } elseif ($data_type == 'object') {

                return json_decode($json);

            }

            return $json;


        }

        public function getRequest()
        {
            $request = parent::getRequest();

            $request += $this->_getRequest(false);

            $request['url'] = str_replace($this->host . '/', '', $request['url']);

            return $request;

        }

        protected function _getRequest($clean = true)
        {
            $url = $this->getUrl('_search');
            $method = 'POST';
            $params = $this->_getParams(false);

            return compact('url', 'method', 'params');
        }

        public function getParams()
        {
            return $this->_getParams(false);
        }

        protected function _getParams($clean = true)
        {

            $params = array();

            if ($this->params) {

                $params = $this->params;

                if ($clean) $this->cleanParams();

            } else {

                $conditions = $this->mode ? $this->mode->getConditions() : array();

                $params = $conditions + array('size' => $this->size, 'from' => $this->from, 'sort' => $this->getOrders()) + $this->_getAggs();
            }

            if (!empty($this->fields)) {
                $params['_source'] = $this->fields;
            }

            if (!empty($this->scriptFields)) {
                $params['script_fields'] = $this->scriptFields;
            }

            return $params;

        }

        public function id($id)
        {

            $url = $this->getUrl($id);

            return $this->getCurl()->setMethod('get')->send($url);

        }

        protected $size = 20;

        public function size($size)
        {

            $this->size = $size;

            return $this;
        }

        protected $from = 0;

        public function from($from)
        {

            $this->from = $from;

            return $this;
        }

        protected $orders = array();

        /**
         * 排序
         * @param string $field
         * @param string $order
         * @param string $mode
         * @return $this
         */
        public function sort($field, $order = 'asc', $mode = null)
        {

            $orders = &$this->orders;

            $other_order_params = array('order' => 'asc');
            if ($order == 'asc') {
                $other_order_params['order'] = 'desc';
            }


            $order_params = array('order' => $order);
            if ($mode) {
                $field_sort_params['mode'] = $mode;
                $other_order_params['mode'] = $mode;
            }

            ($key = array_search(array($field => $other_order_params), $orders)) !== false || ($key = array_search(array($field => $order_params), $orders));

            if ($key !== false) {
                $orders[$key][$field] = $order_params;
            } else {
                $orders[] = array($field => $order_params);
            }

            return $this;

        }

        public function getOrders()
        {

            return $this->orders;

        }

        protected $aggs = array();

        /**
         * 對查詢數據進行分組
         * @param $field
         * @param int $size
         * @param string $name
         * @return $this
         */
        public function aggs($field, $size = 10, $name = null)
        {

            $name = $name ? $name : $field;
            $this->aggs += array($name => array('terms' => array('field' => $field, 'size' => $size)));

            return $this;

        }

        public function setAggs(array $aggs)
        {
            $this->aggs = $aggs;

            return $this;
        }

        public function getAggs()
        {
            return $this->_getAggs(false);
        }

        protected function _getAggs($clean = true)
        {
            $aggs = $this->aggs;

            if ($clean) {

                $this->cleanAggs();

            }

            return empty($aggs) ? array() : array('aggs' => $aggs);

        }

        public function cleanAggs()
        {

            $this->aggs = array();

        }


    }