<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 18:21
     */

    namespace ElasticSearch\ES\Queries\Bool;


    use ElasticSearch\ES\Queries\Words;

    class Should extends Words\Bool
    {

        protected $keyWord = 'should';

    }