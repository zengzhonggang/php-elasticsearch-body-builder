<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\leafQuery;


use ZZG\PhpElasticsearchBodyBuilder\Exception\NoRangeOpException;

class Range extends LeafQueryAbstract
{
    protected $op =[];

    /**
     * @throws NoRangeOpException
     */
    protected function build()
    {
        if (isset($this->value['left']) && isset($this->value['right'])){
            return ['range' => [
                    $this->getField() => [
                        $this->transOp($this->op['left']) => $this->value['left'],
                        $this->transOp($this->op['right']) => $this->value['right']
                    ]
                ]
            ];
        } else {
            $key = array_key_first($this->value);
            return ['range' => [
                    $this->getField() => [
                        $this->transOp($this->op[$key]) => $this->value[$key]
                    ]
                ]
            ];
        }
    }

    public function cover(Range $range) {
        foreach ($range->getValue() as $key => $value) {
            $this->setValue($value,$this->getOp()[$key]);
        }
        return $this;
    }
    /**
     * @throws NoRangeOpException
     */
    public function setValue($value, $op)
    {
        if (in_array($op,['>','>='])) {
            $this->value['left'] = $value;
            $this->op['left'] = $op;
        } elseif (in_array($op,['<','<='])) {
            $this->value['right'] = $value;
            $this->op['right'] = $op;
        } else {
            throw new NoRangeOpException();
        }
    }
    public function getOp()
    {
        return $this->op;
    }

    /**
     * @throws NoRangeOpException
     */
    private function transOp($op)
    {
        switch ($op) {
            case '>':
                $newOp = 'gt';
                break;
            case '>=':
                $newOp = 'gte';
                break;
            case '<':
                $newOp = 'lt';
                break;
            case '<=':
                $newOp = 'lte';
                break;
            default:
                throw new NoRangeOpException();
                break;
        }
        return $newOp;
    }

}