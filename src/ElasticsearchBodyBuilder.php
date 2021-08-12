<?php

namespace ZZG\PhpElasticsearchBodyBuilder;
class ElasticsearchBodyBuilder
{
    public static function createSearchBody(){
        return new Builder\Search\SearchBuilder();
    }

}