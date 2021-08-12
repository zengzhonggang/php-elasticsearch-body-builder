<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\Bool;


class Term extends ConditionAbstract
{
    protected function build()
    {
        return ['term' => [$this->field=>$this->value]];
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}