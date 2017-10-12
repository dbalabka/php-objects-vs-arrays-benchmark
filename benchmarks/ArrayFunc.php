<?php

namespace Php\Bench\ArrayFunc;

function concat(array $a, array $b)
{
    return $a['aaa'] . $b['bbb'];
}

function count_items(array $items)
{
    return count($items);
}
