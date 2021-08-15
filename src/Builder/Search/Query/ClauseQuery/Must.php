<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\ClauseQuery;


class Must extends ClauseQueryAbstract
{
    protected function build()
    {
        return ['must' => $this->buildLeafQuery()];
    }
}