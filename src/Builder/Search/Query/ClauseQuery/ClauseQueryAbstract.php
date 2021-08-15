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
    protected function buildLeafQuery()
    {
         if (count($this->getLeafQuery()) == 1) {
            $result =  $this->getLeafQuery()[0]->toArray();
        } else {
            foreach ($this->getLeafQuery() as $item) {
                $result[] = $item->toArray();
            }
        }
        return $result;
    }
    public function addLeafQuery($query)
    {
        $this->leaf_query[] = $query;
        return $this;
    }

    /**
     * @throws MissLeafQueryException
     */
    public function getLeafQuery()
    {
        if (empty($this->leaf_query)) {
            throw new MissLeafQueryException();
        }
        return $this->leaf_query;
    }
    public function toArray()
    {
        return $this->build();
    }

    public function toJson()
    {
        return json_encode($this->build(),JSON_UNESCAPED_UNICODE);
    }
    abstract protected function build();
}