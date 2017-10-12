<?php

namespace Php\Bench\ObjectFunc;

class SomeClass
{
    public $aaa;
    public $bbb;
    public $ccc;
    public $ddd;
}

class SomeClassWithSetters implements SomeClassInterface
{
    private $aaa;
    private $bbb;
    private $ccc;
    private $ddd;

    public function setAaa($val)
    {
        $this->aaa = $val;
    }

    public function setBbb($val)
    {
        $this->bbb = $val;
    }

    public function setCcc($val)
    {
        $this->ccc = $val;
    }

    public function setDdd($val)
    {
        $this->ddd = $val;
    }

    public function getAaa()
    {
        return $this->aaa;
    }

    public function getBbb()
    {
        return $this->bbb;
    }

    public function getCcc()
    {
        return $this->ccc;
    }
}

class SomeClassWithConstructor implements SomeClassInterface
{
    private $aaa;
    private $bbb;
    private $ccc;
    private $ddd;

    public function __construct($aaa, $bbb, $ddd)
    {
        $this->aaa = $aaa;
        $this->bbb = $bbb;
        $this->ddd = $ddd;
    }

    public function setCcc($val)
    {
        $this->ccc = $val;
    }

    public function getAaa()
    {
        return $this->aaa;
    }

    public function getBbb()
    {
        return $this->bbb;
    }

    public function getCcc()
    {
        return $this->ccc;
    }
}

interface SomeClassInterface
{
    public function getAaa();

    public function getBbb();
}

function concat(SomeClass $a, SomeClass $b)
{
    return $a->aaa . $b->bbb;
}

function concatWithSetters(SomeClassInterface $a, SomeClassInterface $b)
{
    return $a->getAaa() . $b->getBbb();
}

function count_items($items)
{
    return count($items);
}