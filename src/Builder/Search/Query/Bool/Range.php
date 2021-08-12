<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\Search\Query\Bool;


use ZZG\PhpElasticsearchBodyBuilder\Exception\NoRangeOpException;

class Range extends ConditionAbstract
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