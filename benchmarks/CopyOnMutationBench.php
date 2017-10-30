<?php

namespace Php\Bench;

use Php\Bench\ObjectFunc\SomeClass;
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
 * @Groups({"array3"})
 */
class CopyOnMutationBench
{
    protected $arrayOfArrays;
    protected $arrayOfObjects;
    protected $collectionOfObjects;

    public function provideParams()
    {
        return [
            [100000],
        ];
    }

    public function init($params)
    {
        $this->arrayOfArrays = [];
        $this->arrayOfObjects = [];
        $this->collectionOfObjects = new \ArrayObject(array(), \ArrayObject::STD_PROP_LIST);
        for ($i = 0; $i < $params[0]; $i++) {
            $this->arrayOfArrays[] = [
                'aaa' => 1,
                'bbb' => 1,
                'ccc' => 1,
                'ddd' => 1,
            ];
            $this->arrayOfObjects[] = new ObjectFunc\SomeClass(
                1, 1,1,1
            );
            $this->collectionOfObjects[] = new ObjectFunc\SomeClass(
                1, 1,1,1
            );
        }
    }

    public function onAfterMethods()
    {
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchArrayOfArrays()
    {
        $arrayOfArrays = $this->arrayOfArrays;
        $arrayOfArrays = mutateArrayOfArray($arrayOfArrays);
        array_pop($arrayOfArrays);
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchArrayOfObjects()
    {
        $arrayOfObjects = $this->arrayOfObjects;
        $arrayOfObjects = mutateArrayOfObject($arrayOfObjects);
        array_pop($arrayOfObjects);
    }

    /**
     * @ParamProviders({"provideParams"})
     */
    public function benchCollectionOfObjects()
    {
        $collectionOfObjects = $this->collectionOfObjects;
        $collectionOfObjects = mutateArrayOfObject($collectionOfObjects);
        array_pop($collectionOfObjects);
    }

}

function mutateArrayOfArray($arrayOfArrays)
{
    foreach ($arrayOfArrays as $array) {
        $newArray = mutateArrayAndReturn($array);
        $newArray['aaa'] = $newArray['aaa'] + 1;
    }
    $arrayOfArrays[] = [
        'aaa' => 1,
        'bbb' => 1,
        'ccc' => 1,
        'ddd' => 1,
    ];
    return $arrayOfArrays;
}

function mutateArrayOfObject($arrayOfObjects)
{
    foreach ($arrayOfObjects as $object) {
        $newObject = mutateObjectAndReturn($object);
        $newObject->aaa++;
    }
    $arrayOfObjects[] = new ObjectFunc\SomeClass(
        1, 1,1,1
    );
    return $arrayOfObjects;
}

function mutateArrayAndReturn(array $array)
{
    $array['aaa'] = $array['aaa'] + 1;
    $array['bbb'] = $array['bbb'] + 1;
    $array['ccc'] = $array['ccc'] + 1;
    $array['ddd'] = $array['ddd'] + 1;
    return $array;
}

function mutateObjectAndReturn(ObjectFunc\SomeClass $object)
{
    $object->aaa = $object->aaa + 1;
    $object->bbb = $object->bbb + 1;
    $object->ccc = $object->ccc + 1;
    $object->ddd = $object->ddd + 1;
    return $object;
}
