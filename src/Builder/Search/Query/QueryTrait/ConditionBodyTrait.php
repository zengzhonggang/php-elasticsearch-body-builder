<?php

namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\QueryTrait;

use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery\BooleanQuery;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Match;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Range;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Term;

trait ConditionBodyTrait
{
    private $is_ignore_score = false;
    private $condition = [
        'should' =>[],
        'and' =>[],
        'or' => []
    ];
    public function where($field,$op,$value,$isIgnoreScore = null,$isNot = false)
    {
        $this->condition['and'][] =[
            'field' => $field,
            'op' => $op,
            'value' => $value,
            'type' => 'base',
            'is_ignore_score' => $this->getIsIgnoreScore($isIgnoreScore),
            'is_not' => $isNot
        ];
        return $this;
    }
    public function orWhere($field,$op,$value,$isIgnoreScore = null,$isNot = false)
    {
        $this->condition['or'][] =[
            'field' => $field,
            'op' => $op,
            'value' => $value,
            'type' => 'base',
            'is_ignore_score' => $this->getIsIgnoreScore($isIgnoreScore),
            'is_not' => $isNot
        ];
        return $this;
    }
    public function whereGroup(\Closure $groupClosure,$isIgnoreScore = null,$isNot = false) {
        $this->condition['and'][] =[
            'type' => 'group',
            'value' => $groupClosure,
            'is_ignore_score' => $this->getIsIgnoreScore($isIgnoreScore),
            'is_not' => $isNot
        ];
        return $this;
    }
    public function orWhereGroup(\Closure $groupClosure,$isIgnoreScore = null,$isNot = false) {
        $this->condition['or'][] =[
            'type' => 'group',
            'value' => $groupClosure,
            'is_ignore_score' => $this->getIsIgnoreScore($isIgnoreScore),
            'is_not' => $isNot
        ];
        return $this;
    }

    public function whereShould($field,$op,$value,$isIgnoreScore = null,$isNot = false)
    {
        $this->condition['should'][] =[
            'field' => $field,
            'op' => $op,
            'value' => $value,
            'type' => 'base',
            'is_ignore_score' => $this->getIsIgnoreScore($isIgnoreScore),
            'is_not' => $isNot
        ];
        return $this;
    }
    public function whereShouldGroup(\Closure $groupClosure,$isIgnoreScore = null,$isNot = false) {
        $this->condition['should'][] =[
            'type' => 'group',
            'value' => $groupClosure,
            'is_ignore_score' => $this->getIsIgnoreScore($isIgnoreScore),
            'is_not' => $isNot
        ];
        return $this;
    }
    protected function getIsIgnoreScore($isIgnoreScore = null)
    {
        if (is_null($isIgnoreScore)) {
            return $this->is_ignore_score;
        }
        return (bool) $isIgnoreScore;
    }
    private function buildLeafQuery($op, $field, $value)
    {
        $op = $this->operatorSymbolConvert($op);
        if ($op == '=') {
            $condition = new Term();
            $condition->setField($field);
            $condition->setValue($value);
        } elseif ($op == 'like') {
            $condition = new Match();
            $condition->setField($field);
            $condition->setValue($value);
        } elseif (in_array($op,['<','<=','>','>='])) {
            $condition = new Range();
            $condition->setField($field);
            $condition->setValue($value,$op);
        }
        return $condition;
    }

    protected function buildQueryBody()
    {
        $result = [];

        if (!empty($this->condition['and'])) {
            foreach ($this->condition['and'] as $item) {
                $group = !$item['is_not']?($item['is_ignore_score']?'filter':'must'):'must_not';
                if ($item['type'] == 'group') {
                    $newSelf = $this->createNewBooleanQuery($item['is_ignore_score']);
                    $item['value']($newSelf);
                    $result[$group][] = $newSelf;
                } elseif ($item['type'] == 'base') {
                    $condition = $this->buildLeafQuery($item['op'],$item['field'],$item['value']);
                    if ($condition instanceof Range) {
                        if (isset($result[$group][(string)$item['field']])) {
                            $condition = $result[$group][(string)$item['field']]->cover($condition);
                        }
                        $result[$group][(string)$item['field']] = $condition;
                    } else {
                        $result[$group][] = $condition;
                    }
                }
            }
        }
        if (!empty($this->condition['or'])) {
            $bool = $this->createNewBooleanQuery($this->getIsIgnoreScore());
            foreach ($this->condition['or'] as $item) {
                if ($item['type'] == 'group') {
                    $bool=$bool->whereShouldGroup($item['value'],$item['is_ignore_score'],$item['is_not']);
                } elseif($item['type']=='base') {
                    $bool=$bool->whereShould($item['field'],$item['op'],$item['value'],$item['is_ignore_score'],$item['is_not']);
                }
            }
            if (!isset($result['must'])) {
                $result['must'] = [];
            }
            $result['must'][] = $bool;
        }
        if (!empty($this->condition['should'])) {
            $group = 'should';
            foreach ($this->condition['should'] as $item) {
                if ($item['type'] == 'group') {
                    $newSelf = $this->createNewBooleanQuery($item['is_ignore_score']);
                    $item['value']($newSelf);
                    $result[$group][] = $newSelf;
                } elseif ($item['type'] == 'base') {
                    $condition = $this->buildLeafQuery($item['op'],$item['field'],$item['value']);
                    if ($condition instanceof Range) {
                        if (isset($result[$group][(string)$item['field']])) {
                            $condition = $result[$group][(string)$item['field']]->cover($condition);
                        }
                        $result[$group][(string)$item['field']] = $condition;
                    }
                    $result[$group][] = $condition;
                }
            }
        }
        foreach ($result as $group => $arr) {
            foreach ($arr as $k=>$v) {
                $arr[$k] = $v->toArray();
            }
            $result[$group] = array_values($arr);
        }
        return $result;
    }
    public function setIgnoreScore($isIgnoreScore)
    {
        $this->is_ignore_score = $isIgnoreScore;
        return $this;
    }
    private function createNewBooleanQuery($isIgnoreScore)
    {
        return (new BooleanQuery())->setIgnoreScore($isIgnoreScore);
    }
    private function operatorSymbolConvert($op)
    {
        return $op;
    }
}