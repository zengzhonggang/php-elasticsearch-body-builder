<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\Bool;


abstract class ConditionAbstract
{
    protected $field = '';
    protected $value ;
    abstract protected function build();
    public function toArray()
    {
        return $this->build();
    }
    public function getField()
    {
        return $this->field;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function setField($field)
    {
        $this->field = $field;
    }
}