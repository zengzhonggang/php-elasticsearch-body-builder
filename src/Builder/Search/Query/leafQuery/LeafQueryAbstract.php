<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery;


use ZZG\PhpElasticsearchBodyBuilder\Builder\BuilderAbstract;

abstract class LeafQueryAbstract extends BuilderAbstract
{
    protected $field = '';
    protected $value ;
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