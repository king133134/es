<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/4/8
     * Time: 14:07
     */

    namespace ElasticSearch\ES;



    class Action extends Caller
    {

        protected $id;

        public function id($id)
        {

            $this->id = $id;

            return $this;

        }

        public function cleanId()
        {

            return $this->id(null);

        }

        public function mode($mode = 'query')
        {
            return parent::mode('query');
        }

        /**
         * 刪除
         * @return mixed
         */
        public function delete()
        {

            $request = $this->_getDeleteRequest();

            $json = $this->getCurl()->setMethod($request['method'])->send($request['url'], empty($request['params']) ? '' : json_encode($request['params']));

            return json_decode($json, true);


        }

        public function update(array $update_data, $type = 'doc')
        {

            $request = $this->_getUpdateRequest(true, $update_data, $type);

            $json = $this->getCurl()->setMethod($request['method'])->send($request['url'], empty($request['params']) ? '' : json_encode($request['params']));

            return json_decode($json, true);
        }

        public function create(array $data)
        {

            $request = $this->_getCreateRequest();

            $request_params = $data;

            $json = $this->getCurl()->setMethod($request['method'])->send($request['url'], empty($request_params) ? '' : json_encode($request_params));

            return json_decode($json, true);

        }

        public function getParams()
        {
            return $this->_getUpdateOrDeleteRequestParams(false);
        }

        public function getRequest($action = '')
        {
            $request = parent::getRequest();

            $args = func_get_args();
            array_splice($args, 0, 1, array(false));

            switch ($action) {
                case 'update':
                    $request += call_user_func_array(array($this, '_getUpdateRequest'), $args);
                    break;
                case 'delete':
                    $request += $this->_getDeleteRequest(false);
                    break;
                case 'create':
                    $request += $this->_getCreateRequest(false);
                    break;
                default:

            }

            $request['url'] = str_replace($this->host . '/', '', $request['url']);

            return $request;
        }

        protected function _getCreateRequest($clean = true)
        {

            $method = 'PUT';

            if (empty($this->id)) {

                $method = 'POST';
                $url = $this->getUrl();

            } else {

                $url = $this->getUrl($this->id . '/_create');
                if ($clean) $this->cleanId();

            }

            return compact('url', 'method');

        }

        protected $slice;

        public function slice($slices_or_max, $id = null)
        {
            if (is_numeric($id)) {
                $this->slice = array('max' => $slices_or_max, 'id' => $id);
            } else {
                $this->slice = $slices_or_max;
            }

            return $this;

        }

        public function cleanSlice()
        {

            $this->slice = null;

            return $this;

        }

        /**
         * 刪除index
         * @return mixed
         */
        public function delIndex()
        {
            $method = 'DELETE';
            $index = $this->getIndex();

            $url = $this->getHost() . '/' . $index;

            $json = $this->getCurl()->setMethod($method)->send($url);

            return json_decode($json, true);
        }

        /**
         * 更新index
         * @param $json
         * @return mixed
         */
        public function putIndex($json)
        {
            $method = 'PUT';
            $index = $this->getIndex();

            $url = $this->getHost() . '/' . $index;

            $json = $this->getCurl()->setMethod($method)->send($url, $json);

            return json_decode($json, true);
        }

        protected function _getDeleteRequest($clean = true)
        {

            $method = 'DELETE';

            $slice_arr = array();

            $params = $this->_getUpdateOrDeleteRequestParams();

            if ($this->id) {

                $url = $this->getUrl($this->id);

                if ($clean) $this->cleanId();

            } else {
                $url = $this->getUrl('_delete_by_query?conflicts=proceed');
                $method = 'POST';

                if ($this->slice) {

                    $slice = $this->slice;

                    if (is_array($slice)) {
                        $slice_arr = array(
                            'slice' => $slice,
                        );
                    } else {
                        $url = $url . '&slices=' . $slice;
                    }
                }
            }

            $params += $slice_arr;

            return compact('url', 'method', 'params');

        }

        protected function _getUpdateRequest($clean = true, array $update_data, $type = 'doc')
        {
            $method = 'POST';
            $slice_arr = array();

            if ($this->id) {

                $url = $this->getUrl($this->id . '/_update');

                if ($clean) $this->cleanId();

            } else {
                $url = $this->getUrl('_update_by_query?conflicts=proceed');
                $method = 'POST';

                if ($this->slice) {

                    $slice = $this->slice;

                    if (is_array($slice)) {
                        $slice_arr = array(
                            'slice' => $slice,
                        );
                    } else {
                        $url = $url . '&slices=' . $slice;
                    }
                }
            }


            $params = $this->_getUpdateOrDeleteRequestParams($clean);
            /**
             * 如果有參數，只能使用script
             */
            if (!empty($params) && $type != 'script') {
                $script = '';
                foreach ($update_data as $key => $datum) {
                    $script .= sprintf('ctx._source.%s = %s', $key, is_string($datum) ? "'" . $datum . "'" : $datum) . ';';
                }
                $type = 'script';
                $update_data = $script;
            }

            $params += array($type => $update_data) + $slice_arr;

            return compact('url', 'method', 'params');

        }

        protected function _getUpdateOrDeleteRequestParams($clean = true)
        {


            $params = array();

            if ($this->id) {

            } else if ($this->params) {

                $params = $this->params;

                if ($clean) $this->cleanParams();

            } else {

                $params = $this->mode ? $this->mode->getConditions() : array();

            }


            return $params;

        }

    }