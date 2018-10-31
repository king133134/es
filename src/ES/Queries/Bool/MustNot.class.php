<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 2018/3/28
     * Time: 18:17
     */

    namespace Addcn\Model\ES\Queries\Bool;


    use Addcn\Model\ES\Queries\Words;

    class MustNot extends Words\Bool
    {

        protected $keyWord = 'must_not';

    }