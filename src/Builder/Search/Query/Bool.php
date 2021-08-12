<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query;


class Bool
{
    private $condition = [
        'must' =>[],
        'filter' => [],
        'should' => [],
        'must_not' =>[]
    ];

    private $is_ignore_score = false;

    public function where($field,$op,$value,$isIgnoreScore = null)
    {

    }

    public function whereNot($field,$op,$value,$isIgnoreScore = null)
    {

    }

    public function orWhere($field,$op,$value,$isIgnoreScore = null)
    {

    }

    public function orWhereNot($field,$op,$value,$isIgnoreScore = null)
    {

    }

    public function whereBetween($field,$lop,$lvalue,$rop,$rvalue,$isIgnoreScore = null)
    {

    }
    public function orWhereBetween($field,$lop,$lvalue,$rop,$rvalue,$isIgnoreScore = null)
    {

    }

    public function whereNotBetween($field,$lop,$lvalue,$rop,$rvalue,$isIgnoreScore = null)
    {

    }
    public function orWhereNotBetween($field,$lop,$lvalue,$rop,$rvalue,$isIgnoreScore = null)
    {

    }
    public function setIgnoreScore($bool)
    {
        $this->is_ignore_score = (bool)$bool;
        return $this;
    }

    private function getIsIgnoreScore($isIgnoreScore = null)
    {
        if (is_null($isIgnoreScore)) {
            return $this->is_ignore_score;
        }
        return (bool) $isIgnoreScore;
    }
    private function createCondition($conditionGroup,$op,$field,$value)
    {

    }

    
}