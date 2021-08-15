<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\ClauseQuery;


class MustNot extends ClauseQueryAbstract
{
    protected function build()
    {
        return ['mustNot' => $this->buildLeafQuery()];
    }
}