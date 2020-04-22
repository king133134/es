<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 18:17
     */

    namespace ElasticSearch\ES\Queries\Bool;


    use ElasticSearch\ES\Queries\Words;

    class MustNot extends Words\Boolean
    {

        protected $keyWord = 'must_not';

    }