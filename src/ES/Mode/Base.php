<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 16:56
     */

    namespace ElasticSearch\ES\Mode;


    use ElasticSearch\ES\Caller;
    use ElasticSearch\ES\Queries\Words\Boolean;

    class Base extends Boolean
    {

        protected $conditionMultiple = false;

    }