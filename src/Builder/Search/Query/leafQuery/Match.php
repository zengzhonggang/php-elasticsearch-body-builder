<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery;


class Match extends LeafQueryAbstract
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