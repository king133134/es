<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/4/25
     * Time: 17:36
     */

    namespace ElasticSearch\ES\Queries\Bool;
    use ElasticSearch\ES\Queries\Words;


    class Filter extends Words\Bool
    {

        protected $keyWord = 'filter';

    }