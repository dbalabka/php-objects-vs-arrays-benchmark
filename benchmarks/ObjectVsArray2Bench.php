<?php

namespace Php\Bench;

use PhpBench\Benchmark\Metadata\Annotations\BeforeClassMethods;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\AfterMethods;
use PhpBench\Benchmark\Metadata\Annotations\Groups;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Sleep;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;

require_once __DIR__ . '/ArrayFunc.php';
require_once __DIR__ . '/ObjectFunc.php';

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"onAfterMethods"})
 * @Iterations(5)
 * @Revs(1)
 * @Warmup(1)
 * @OutputTimeUnit("milliseconds", precision=5)
 * @Sleep(1000)
 * @Groups({"array2"})
 */
class ObjectVsArray2Bench
{
    public function provideParams()
    {
        return [
            [100000],
        ];
    }

    public function init()
    {
    }

    public function onAfterMethods()
    {
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchArray($params)
    {
        $a = new A();
        for ($i = 0; $i < $params[0]; $i++) {
            $r = 1; // rand(0, 1);
            $r ? $a->setA($i) : $a->setB($i);
            $t = $r ? $a->getA() : $a->getB();
        }
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchObject($params)
    {
        $b = new B();
        for ($i = 0; $i < $params[0]; $i++) {
            $r = 1; // rand(0, 1);
            $r ? $b->a = $i : $b->b = $i;
            $t = $r ? $b->a : $b->b;
        }
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchObjectSetters($params)
    {
        $a = [1, 2];
        for ($i = 0; $i < $params[0]; $i++) {
            $r = 1; // rand(0, 1);
            $r ? $a[0] = $i : $a[1] = $i;
            $t = $r ? $a[0] : $a[1];
        }
    }

}

class A {
    private $a = 1;
    private $b = 2;

    public function getA() {
        return $this->a;
    }

    public function setA($a) {
        $this->a = $a;
    }

    public function getB() {
        return $this->b;
    }

    public function setB($b) {
        $this->b = $b;
    }
}

class B {
    public $a = 1;
    public $b = 2;
}