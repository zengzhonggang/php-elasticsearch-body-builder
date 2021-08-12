<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder;


abstract class BuilderAbstract
{
    public function toArray(){
        return $this->build();
    }
    public function toJson()
    {
        return json_encode($this->build(),JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return array
     */
    abstract protected function build();
}