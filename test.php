<?php
class Test
{
    protected $foo = [];
    public function tester($bar)
    {
        $this->foo[] = &$bar;
    }
}