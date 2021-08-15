<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\ClauseQuery;


use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Match;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Range;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Term;
use ZZG\PhpElasticsearchBodyBuilder\Exception\MissLeafQueryException;

abstract class ClauseQueryAbstract
{
    /**
     * @var  Match[] | Range [] | Term []
     */
    private $leaf_query = [];

    /**
     * @return array
     * @throws MissLeafQueryException
     */
    protected function build()
    {
        if (empty($this->leaf_query)) {
            throw new MissLeafQueryException();
        } else if (count($this->leaf_query) == 1) {
            $result =  $this->leaf_query[0]->toArray();
        } elseif (count($this->leaf_query) > 1) {
            foreach ($this->leaf_query as $item) {
                $result[] = $item->toArray();
            }
        }
        return $result;
    }
    public function toArray()
    {
        return $this->build();
    }

    public function toJson()
    {
        return json_encode($this->build(),JSON_UNESCAPED_UNICODE);
    }
}