<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\ClauseQuery;


class Filter extends ClauseQueryAbstract
{

    protected function build()
    {
        return ['filter' => $this->buildLeafQuery()];
    }
}