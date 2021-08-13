<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query;


use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\Bool\Match;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\Bool\Range;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\Bool\Term;
use ZZG\PhpElasticsearchBodyBuilder\Exception\NoRangeOpException;

class BoolAttr
{
    private $condition = [
        'should' =>[],
        'and' =>[],
        'or' => []
    ];

    private $is_ignore_score = false;

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
    public function toArray(){
        return $this->build();
    }
    private function build()
    {
        $result = ['bool' => []];
        if (!empty($this->condition['and'])) {
            foreach ($this->condition['and'] as $item) {
                $group = !$item['is_not']?($item['is_ignore_score']?'filter':'must'):'must_not';
                if ($item['type'] == 'group') {
                    $result['bool'][$group][] = $item['value']($this->createNewSelf($item['is_ignore_score']));
                } elseif ($item['type'] == 'base') {
                    $condition = $this->buildCondition($item['op'],$item['field'],$item['value']);
                    if ($condition instanceof Range) {
                        if (isset($result['bool'][$group][(string)$item['field']])) {
                            $condition = $result['bool'][$group][(string)$item['field']]->cover($condition);
                        }
                        $result['bool'][$group][(string)$item['field']] = $condition;
                    } else {
                        $result['bool'][$group][] = $condition;
                    }
                }
            }
        }
        if (!empty($this->condition['or'])) {
            $bool = $this->createNewSelf($this->getIsIgnoreScore());
            foreach ($this->condition['or'] as $item) {
                if ($item['type'] == 'group') {
                    $bool=$bool->whereShouldGroup($item['value'],$item['is_ignore_score'],$item['is_not']);
                } elseif($item['type']=='base') {
                    $bool=$bool->whereShould($item['field'],$item['op'],$item['value'],$item['is_ignore_score'],$item['is_not']);
                }
            }
            if (!isset($result['bool']['must'])) {
                $result['bool']['must'] = [];
            }
            $result['bool']['must'][] = $bool;
        }
        if (!empty($this->condition['should'])) {
            $group = 'should';
            foreach ($this->condition['should'] as $item) {
                if ($item['type'] == 'group') {
                    $result['bool'][$group][] = $item['value']($this->createNewSelf($item['is_ignore_score']));
                } elseif ($item['type'] == 'base') {
                    $condition = $this->buildCondition($item['op'],$item['field'],$item['value']);
                    if ($condition instanceof Range) {
                        if (isset($result['bool'][$group][(string)$item['field']])) {
                            $condition = $result['bool'][$group][(string)$item['field']]->cover($condition);
                        }
                        $result['bool'][$group][(string)$item['field']] = $condition;
                    }
                    $result['bool'][$group][] = $condition;
                }
            }
        }
        foreach ($result['bool'] as $group => $arr) {
            foreach ($arr as $k=>$v) {
                $arr[$k] = $v->toArray();
            }
            $result['bool'][$group] = array_values($arr);
        }
        return $result;
    }
    private function buildCondition($op,$field,$value)
    {
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
    private function createNewSelf($isIgnoreScore)
    {
        return (new self())->setIgnoreScore($isIgnoreScore);
    }
}