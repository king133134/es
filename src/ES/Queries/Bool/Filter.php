<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/4/25
     * Time: 17:36
     */

    namespace Addcn\Model\ES\Queries\Bool;
    use Addcn\Model\ES\Queries\Words;


    class Filter extends Words\Bool
    {

        protected $keyWord = 'filter';

    }