<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery;


class Term extends LeafQueryAbstract
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