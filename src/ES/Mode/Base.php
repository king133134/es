<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 16:56
     */

    namespace Addcn\Model\ES\Mode;


    use Addcn\Model\ES\Caller;
    use Addcn\Model\ES\Queries\Words\Bool;

    class Base extends Bool
    {

        protected $conditionMultiple = false;

    }