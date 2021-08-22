<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search;


use ZZG\PhpElasticsearchBodyBuilder\Builder\BuilderAbstract;
use ZZG\PhpElasticsearchBodyBuilder\Builder\PublicTrait\OptionTrait;

class SearchBuilder extends BuilderAbstract
{
    use OptionTrait;
    protected function build()
    {

    }

    protected function buildQueryOption()
    {
        return [];
    }
}