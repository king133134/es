<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/29
     * Time: 19:28
     */

    namespace ElasticSearch\ES;


    use ElasticSearch\ES\Mode\Base;
    use ElasticSearch\ES\Exceptions\InvalidCallException;
    use ElasticSearch\Request\CurlNormal;

    class Caller
    {

        protected $index;

        public function getIndex()
        {
            return $this->index;
        }

        protected $type;

        public function getType()
        {
            return $this->type;
        }

        protected $host;

        public function getHost()
        {
            return $this->host;
        }


        /**
         * 搜尋模式
         * @see Base
         * @var object
         */
        protected $mode;


        public function __construct($host)
        {
            $this->host = $host;
        }

        protected static $curlResource;


        protected static $header = array('Content-Type: application/json');

        /**
         * @param string $method
         * @return CurlNormal
         */
        public static function getCurlResource($method = 'get')
        {

            if (static::$curlResource) {
                return static::$curlResource;
            }

            static::$curlResource = new CurlNormal($method);

            static::$curlResource->setHeader(static::$header);

            return static::$curlResource;

        }

        /**
         * @return CurlNormal
         */
        public function getCurl()
        {

            return static::getCurlResource();

        }


        public function index($index)
        {

            $this->index = $index;

            return $this;

        }

        /**
         * @param $type
         * @return $this
         */
        public function type($type)
        {

            $this->type = $type;

            return $this;

        }

        /**
         * @param $mode
         * @return Mode\Base
         * @throws InvalidCallException
         */
        public function mode($mode)
        {

            if ($this->mode) {
                return $this->mode;
            }


            $modes = array('query' => 'ElasticSearch\ES\Mode\Query', 'post_filter' => 'ElasticSearch\ES\Mode\PostFilter');

            if (!isset($modes[$mode])) {
                throw new InvalidCallException('mode is invalid!');
            }

            /**
             * @see Base
             */
            $this->mode = new $modes[$mode]($this);


            return $this->mode;

        }


        protected $params;

        public function params(array $params)
        {
            $this->params = $params;

            return $this;
        }


        public function cleanParams()
        {
            $this->params = null;

            return $this;
        }


        protected function getUrl($id_or_mode = '')
        {

            $props = array('host', 'index', 'type');

            $paths = array();
            foreach ($props as $prop) {
                if ($this->$prop) {
                    $paths[] = $this->$prop;
                }
            }

            return implode('/', $paths) . '/' . $id_or_mode;

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

        public function getParams()
        {

        }

        public function getRequest()
        {

            $header = static::$header;

            return compact('header');

        }


    }