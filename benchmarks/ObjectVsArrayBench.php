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
 * @Iterations(20)
 * @Revs(1)
 * @Warmup(1)
 * @OutputTimeUnit("milliseconds", precision=5)
 * @Sleep(1000)
 * @Groups({"array"})
 */
class ObjectVsArrayBench
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
        $arraysOf = array();
        for ($i = 0; $i < $params[0]; $i++) {
            $z = [];
            $z['aaa'] = 'aaa';
            $z['bbb'] = 'bbb';
            $a['ddd'] = ['ddd'];
            $z['ccc'] = ArrayFunc\concat($z, $z);
            $arraysOf[] = $z;
            ArrayFunc\count_items($arraysOf);
        }
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchObject($params)
    {
        $arraysOf = array();
        for ($i = 0; $i < $params[0]; $i++) {
            $z = new ObjectFunc\SomeClass();
            $z->aaa = 'aaa';
            $z->bbb = 'bbb';
            $z->ddd = ['ddd'];
            $z->ccc = ObjectFunc\concat($z, $z);
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchObjectCollection($params)
    {
        $arraysOf = new \ArrayObject(array(), \ArrayObject::STD_PROP_LIST);
        for ($i = 0; $i < $params[0]; $i++) {
            $z = new ObjectFunc\SomeClass();
            $z->aaa = 'aaa';
            $z->bbb = 'bbb';
            $z->ddd = ['ddd'];
            $z->ccc = ObjectFunc\concat($z, $z);
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchObjectSetters($params)
    {
        $arraysOf = array();
        for ($i = 0; $i < $params[0]; $i++) {
            $z = new ObjectFunc\SomeClassWithSetters();
            $z->setAaa('aaa');
            $z->setBbb('bbb');
            $z->setDdd(['ddd']);
            $z->setCcc(ObjectFunc\concatWithSetters($z, $z));
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchObjectSettersConstruct($params)
    {
        $arraysOf = array();
        for ($i = 0; $i < $params[0]; $i++) {
            $z = new ObjectFunc\SomeClassWithConstructor('aaa', 'bbb', ['ddd']);
            $z->setCcc(ObjectFunc\concatWithSetters($z, $z));
            $arraysOf[] = $z;
            ObjectFunc\count_items($arraysOf);
        }
    }
}