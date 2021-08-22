<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery;


use ZZG\PhpElasticsearchBodyBuilder\Builder\BuilderAbstract;
use ZZG\PhpElasticsearchBodyBuilder\Builder\PublicTrait\OptionTrait;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Match;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Range;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery\Term;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\QueryTrait\ConditionBodyTrait;
use ZZG\PhpElasticsearchBodyBuilder\Exception\NoRangeOpException;

class BooleanQuery extends BuilderAbstract
{
    use OptionTrait,ConditionBodyTrait;

    const BOOST = 'boost';
    const MINIMUM_SHOULD_MATCH = 'minimum_should_match';

    public function toArray(){
        return $this->build();
    }

    public function setMinimumShouldMatch($value)
    {
        $this->setOption(self::MINIMUM_SHOULD_MATCH,$value);
        return $this;
    }
    public function setBoost($value)
    {
        $this->setOption(self::BOOST,$value);
        return $this;
    }

    protected function build()
    {
        return ['bool' => $this->buildQueryBody()];
    }

    protected function buildQueryOption()
    {
        return $this->getOptionArray([self::MINIMUM_SHOULD_MATCH,self::BOOST]);
    }
}