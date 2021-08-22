<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search;


use ZZG\PhpElasticsearchBodyBuilder\Builder\BuilderAbstract;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery\BooleanQuery;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery\BoostingQuery;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery\ConstantScoreQuery;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\QueryTrait\ConditionBodyTrait;
use ZZG\PhpElasticsearchBodyBuilder\Exception\QueryParameterConflictException;

class Query extends BuilderAbstract
{

    use ConditionBodyTrait;
    private $subQuery;
    protected function build()
    {
        $body = $this->buildQueryBody();
        if ($body) {
            if ($this->subQuery) {
                if ($this->subQuery instanceof BooleanQuery) {
                    $subBody = $this->subQuery->toArray();
                    foreach ($body as $key => $item){
                        if (isset($subBody['bool'][$key])) {
                            $subBody['bool'][$key] = array_merge($subBody['bool'][$key],$item);
                        } else {
                            $subBody['bool'][$key] = $item;
                        }
                    }
                    $body = $subBody;
                }else {
                    throw new QueryParameterConflictException();
                }
            } else {
                $body = ['bool' => $body];
            }
        }elseif($this->subQuery) {
            $body = $this->subQuery->toArray();
        }
        return $body;
    }

    public function booleanQuery()
    {
        $this->checkSubQueryConflict($this->subQuery instanceof BooleanQuery);
        $this->subQuery = new BooleanQuery();
        return $this->subQuery;
    }
    public function boostingQuery()
    {
        $this->checkSubQueryConflict($this->subQuery instanceof BoostingQuery);
        $this->subQuery = new BoostingQuery();
        return $this->subQuery;
    }

    public function constantScoreQuery()
    {
        $this->checkSubQueryConflict($this->subQuery instanceof ConstantScoreQuery);
        $this->subQuery = new ConstantScoreQuery();
        return $this->subQuery;
    }
    private function checkSubQueryConflict($and)
    {
        if (!$this->subQuery || !$and) {
            throw new QueryParameterConflictException();
        }
    }
}