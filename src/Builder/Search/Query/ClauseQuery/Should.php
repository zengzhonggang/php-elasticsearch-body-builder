<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\ClauseQuery;


class Should extends ClauseQueryAbstract
{
    protected function build()
    {
        return ['should' => $this->buildLeafQuery()];
    }
}