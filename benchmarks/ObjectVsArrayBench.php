<?php

namespace Php\Bench;

use PhpBench\Benchmark\Metadata\Annotations\BeforeClassMethods;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\AfterMethods;
use PhpBench\Benchmark\Metadata\Annotations\Groups;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Sleep;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;

require_once __DIR__ . '/ArrayFunc.php';
require_once __DIR__ . '/ObjectFunc.php';

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"onAfterMethods"})
 * @Iterations(20)
 * @Revs(1)
 * @OutputTimeUnit("milliseconds", precision=5)
 * @Sleep(1000)
 * @Groups({"array"})
 */
class ObjectVsArrayBench
{
    const ITERATIONS_NUM = 10000;

    public function init()
    {
    }

    public function onAfterMethods()
    {
    }

    public function benchArray()
    {
        $arraysOf = array();
        for ($i = 0; $i < self::ITERATIONS_NUM; $i++) {
            $z = [];
            $z['aaa'] = 'aaa';
            $z['bbb'] = 'bbb';
            $a['ddd'] = ['ddd'];
            $z['ccc'] = ArrayFunc\concat($z, $z);
            $arraysOf[] = $z;
            ArrayFunc\count_items($arraysOf);
        }
    }

    public function benchObject()
    {
        $arraysOf = array();
        for ($i = 0; $i < self::ITERATIONS_NUM; $i++) {
            $z = new ObjectFunc\SomeClass();
            $z->aaa = 'aaa';
            $z->bbb = 'bbb';
            $z->ddd = ['ddd'];
            $z->ccc = ObjectFunc\concat($z, $z);
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }

    public function benchObjectCollection()
    {
        $arraysOf = new \ArrayObject(array(), \ArrayObject::STD_PROP_LIST);
        for ($i = 0; $i < self::ITERATIONS_NUM; $i++) {
            $z = new ObjectFunc\SomeClass();
            $z->aaa = 'aaa';
            $z->bbb = 'bbb';
            $z->ddd = ['ddd'];
            $z->ccc = ObjectFunc\concat($z, $z);
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }

    public function benchObjectSetters()
    {
        $arraysOf = array();
        for ($i = 0; $i < self::ITERATIONS_NUM; $i++) {
            $z = new ObjectFunc\SomeClassWithSetters();
            $z->setAaa('aaa');
            $z->setBbb('bbb');
            $z->setDdd(['ddd']);
            $z->setCcc(ObjectFunc\concatWithSetters($z, $z));
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }

    public function benchObjectSettersConstruct()
    {
        $arraysOf = array();
        for ($i = 0; $i < self::ITERATIONS_NUM; $i++) {
            $z = new ObjectFunc\SomeClassWithConstructor('aaa', 'bbb', ['ddd']);
            $z->setCcc(ObjectFunc\concatWithSetters($z, $z));
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }
}