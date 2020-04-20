<?php
    /**
     * es查询关键字
     */

    namespace ElasticSearch\ES\Queries\Words;


    use ElasticSearch\ES\Caller;

    class Common extends Base
    {


        public function __construct(Caller $caller)
        {
            parent::__construct($caller);

        }

        public function term($field, $value, $constantScore = null)
        {

            $condition = array('term' => array($field => $value));

            return $this->_setCondition($condition, $constantScore);

        }

        public function terms($field, array $values, $constantScore = null)
        {
            $condition = array('terms' => array($field => $values));

            return $this->_setCondition($condition, $constantScore);
        }

        public function match($field, $value, $operator = null, $constantScore = null)
        {
            //to do match 查询支持 minimum_should_match 最小匹配参数

            $condition = array();
            if (!$operator) {

                $condition = array('match' => array($field => $value));

            } else {
                if (is_string($operator)) {

                    $condition = array('match' => array($field => array('query' => $value, 'operator' => $operator)));

                } else {
                    if (is_array($operator)) {

                        $condition = array('match' => array($field => (array('query' => $value) + $operator)));

                    }
                }
            }

            return $this->_setCondition($condition, $constantScore);

        }

        public function multiMatch(array $fields, $value, array $params = null, $constantScore = null)
        {
            $condition = array(
                'multi_match' => array(
                    'query'  => $value,
                    'fields' => $fields,
                ),
            );

            if ($params) {
                $condition['multi_match'] += $params;
            }

            return $this->_setCondition($condition, $constantScore);

        }


        public function range($field, $operator, $value, $constantScore = null)
        {

            $operator_map = array(
                '>'  => 'gt',
                '>=' => 'gte',
                '<'  => 'lt',
                '<=' => 'lte',
            );

            $condition = array('range' => array($field => array($operator_map[$operator] => $value)));

            return $this->_setCondition($condition, $constantScore);

        }

        public function between($field, $min, $max, $constantScore = null)
        {
            $condition = array('range' => array($field => array('gte' => $min, 'lte' => $max)));

            return $this->_setCondition($condition, $constantScore);
        }

        public function exists($field, $constantScore = null)
        {
            $condition = array('exists' => array('field' => $field));

            return $this->_setCondition($condition, $constantScore);
        }


        public function missing($field, $constantScore = null)
        {
            $condition = array('missing' => array('field' => $field));

            return $this->_setCondition($condition, $constantScore);
        }

        public function prefix($field, $value, $params = null, $constantScore = null)
        {

            $condition = array(
                'prefix' => array(
                    $field => array('value' => $value)
                ),
            );

            if (is_numeric($params)) {
                $condition['prefix'][$field] += array('boost' => $params);
            } else {
                if (is_array($params)) {
                    $condition['prefix'][$field] += $params;
                }
            }

            return $this->_setCondition($condition, $constantScore);

        }

        public function wildcard($field, $value, $params = null, $constantScore = null)
        {

            $condition = array(
                'wildcard' => array(
                    $field => array('value' => $value)
                ),
            );

            if (is_numeric($params)) {
                $condition['wildcard'][$field] += array('boost' => $params);
            } else {
                if (is_array($params)) {
                    $condition['wildcard'][$field] += $params;
                }
            }

            return $this->_setCondition($condition, $constantScore);

        }

        public function regexp($field, $value, $params = null, $constantScore = null)
        {

            $condition = array(
                'regexp' => array(
                    $field => array('value' => $value)
                ),
            );

            if (is_numeric($params)) {
                $condition['regexp'][$field] += array('boost' => $params);
            } else {
                if (is_array($params)) {
                    $condition['regexp'][$field] += $params;
                }
            }


            return $this->_setCondition($condition, $constantScore);

        }

        public function fuzzy($field, $value, $params = null, $constantScore = null)
        {

            $condition = array(
                'fuzzy' => array(
                    $field => array('value' => $value)
                ),
            );

            if (is_numeric($params)) {
                $condition['fuzzy'][$field] += array('boost' => $params);
            } else {
                if (is_array($params)) {
                    $condition['fuzzy'][$field] += $params;
                }
            }


            return $this->_setCondition($condition, $constantScore);

        }

        public function termsSet($field, array $values, $shouldMatchMinimum = 1, $constantScore = null)
        {
            $condition = array(
                'terms_set' => array(
                    $field => array(
                        'terms'                       => $values,
                        'minimum_should_match_script' => array(
                            'source' => (string)$shouldMatchMinimum
                        )
                    )
                )
            );

            return $this->_setCondition($condition, $constantScore);
        }

        public function termsSetByField($field, array $values, $shouldMatchMinimumField, $constantScore = null)
        {
            $condition = array(
                'terms_set' => array(
                    $field => array(
                        'terms'                      => $values,
                        'minimum_should_match_field' => $shouldMatchMinimumField
                    )
                )
            );

            return $this->_setCondition($condition, $constantScore);
        }


    }