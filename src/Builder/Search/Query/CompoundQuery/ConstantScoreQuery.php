<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery;


use ZZG\PhpElasticsearchBodyBuilder\Builder\BuilderAbstract;
use ZZG\PhpElasticsearchBodyBuilder\Builder\PublicTrait\OptionTrait;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\QueryTrait\ConditionBodyTrait;

class ConstantScoreQuery extends BuilderAbstract
{
    use OptionTrait,ConditionBodyTrait;
    const BOOST = 'boost';
    public function setBoost($value)
    {
        $this->setOption(self::BOOST,$value);
        return $this;
    }
    /**
     * @inheritDoc
     */
    protected function build()
    {
        $result = $this->buildQueryOption();
        $result['filter'] = $this->buildQueryBody();
        return [
            'constant_score' => $result
        ];
    }

    private function getIsIgnoreScore($isIgnoreScore = null)
    {
        return true;
    }
    protected function buildQueryOption()
    {
        return $this->getOptionArray([self::BOOST]);
    }
}