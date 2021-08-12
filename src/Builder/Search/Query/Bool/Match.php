<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\Bool;


class Match extends ConditionAbstract
{

    protected function build()
    {
        return ['match' => [$this->field=>$this->value]];
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}