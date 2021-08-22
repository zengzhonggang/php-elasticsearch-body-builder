<?php


namespace ZZG\PhpElasticsearchBodyBuilder\Builder\PublicTrait;


trait OptionTrait
{
    protected $option = [];

    /**
     * @return array
     */
    abstract protected function buildQueryOption();

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

    private function getOptionArray($keys)
    {
        $options = [];
        foreach ($keys as $key) {
            if ($this->issetOption($key)) {
                $options[$key] = $this->getOption($key);
            }
        }
        return $options;
    }
}