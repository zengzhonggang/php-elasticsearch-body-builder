<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery;


use ZZG\PhpElasticsearchBodyBuilder\Builder\BuilderAbstract;
use ZZG\PhpElasticsearchBodyBuilder\Builder\PublicTrait\OptionTrait;

class ConstantScoreQuery extends BuilderAbstract
{
    use OptionTrait;
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
        // TODO: Implement build() method.
    }
}