<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 17:59
     */

    namespace Addcn\Model\ES\Queries;


    use Addcn\Model\ES\Caller;
    use Addcn\Model\Exception\InvalidCallException;

    abstract class Base
    {

        protected $keyWord;

        protected $caller;

        public function __construct(Caller $caller)
        {
            $this->caller = $caller;
        }

        abstract public function getConditions();

        public function __call($name, $arguments)
        {

            $result = call_user_func_array(array($this->caller, $name), $arguments);

            if ($result instanceof Caller) {
                return $this;
            }

            return $result;

        }


    }