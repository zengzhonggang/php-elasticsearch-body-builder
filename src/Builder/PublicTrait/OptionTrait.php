<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\PublicTrait;


trait OptionTrait
{
    protected $option = [];

    private function setOption($key,$value)
    {
        $this->option[$key] = $value;
    }
    private function issetOption($key){
        return isset($this->option[$key]);
    }
    private function getOption($key)
    {
        return $this->option[$key];
    }

}