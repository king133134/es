<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 18:01
     */

    namespace ElasticSearch\ES\Queries;


    use ElasticSearch\ES\Caller;
    use ElasticSearch\ES\Queries\Bool\Must;
    use ElasticSearch\ES\Queries\Bool\MustNot;
    use ElasticSearch\ES\Queries\Bool\Should;
    use ElasticSearch\ES\Queries\Bool\Filter;

    class Boolean extends Base
    {

        protected $keyWord = 'bool';

        protected $must;
        protected $mustNot;
        protected $should;
        protected $filter;

        public function __construct(Caller $caller)
        {
            parent::__construct($caller);
        }

        public function must()
        {

            if ($this->must) {
                return $this->must;
            }
            $this->must = new Must($this->caller);

            return $this->must;

        }

        public function mustNot()
        {

            if ($this->mustNot) {
                return $this->must;
            }
            $this->mustNot = new MustNot($this->caller);

            return $this->mustNot;

        }

        public function should()
        {

            if ($this->should) {
                return $this->should;
            }
            $this->should = new Should($this->caller);

            return $this->should;

        }

        public function filter()
        {

            if ($this->filter) {
                return $this->filter;
            }
            $this->filter = new Filter($this->caller);

            return $this->filter;

        }

        public function getConditions()
        {
            // TODO: Implement getConditions() method.

            $result = array();

            foreach ($this as $key => $attr) {
                if (is_object($attr) && $attr instanceof Base) {
                    $conditions = $attr->getConditions();
                    if (empty($conditions)) {
                        continue;
                    }
                    $result += $conditions;
                } else if(is_numeric($attr)){
                    $result[$key] = $attr;
                }
            }

            if (empty($result)) {
                return $result;
            }

            return array('bool' => $result);

        }

        protected $minimum_should_match;

        public function setMinimumShouldMatch($num)
        {

            $this->minimum_should_match = $num;

            return $this;

        }

        protected $boost;

        public function setBoost($boost)
        {

            $this->boost = $boost;

            return $this;

        }

    }