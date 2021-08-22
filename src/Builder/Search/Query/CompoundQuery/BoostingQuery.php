<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\CompoundQuery;


use ZZG\PhpElasticsearchBodyBuilder\Builder\BuilderAbstract;
use ZZG\PhpElasticsearchBodyBuilder\Builder\PublicTrait\OptionTrait;
use ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query;
use ZZG\PhpElasticsearchBodyBuilder\Exception\MissNegativeBoostException;
use ZZG\PhpElasticsearchBodyBuilder\Exception\MissNegativeException;
use ZZG\PhpElasticsearchBodyBuilder\Exception\MissPositiveException;

class BoostingQuery extends BuilderAbstract
{
    use OptionTrait;
    private $positive;
    private $negative;

    const NEGATIVE_BOOST = 'negative_boost';
    public function positive(\Closure $closure)
    {
        $query = new Query();
        $closure($query);
        $this->positive = $query;
        return $this;
    }
    public function negative(\Closure $closure)
    {
        $query = new Query();
        $closure($query);
        $this->negative = $query;
        return $this;
    }
    public function setNegativeBoost($value)
    {
        $this->setOption(self::NEGATIVE_BOOST,$value);
    }
    protected function build()
    {
        if (empty($this->positive)){
            throw new MissPositiveException();
        }
        if (empty($this->negative)){
            throw new MissNegativeException();
        }
        if (!$this->issetOption(self::NEGATIVE_BOOST)){
            throw new MissNegativeBoostException();
        }
        return [
            'boosting' => [
                'positive' => $this->positive->toArray(),
                'negative' => $this->negative->toArray(),
                'negative_boost' => $this->getOption(self::NEGATIVE_BOOST)
            ]
        ];
    }

    protected function buildQueryOption()
    {
        return [];
    }
}