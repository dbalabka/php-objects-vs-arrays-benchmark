TL;DR
==========
There no benefit to fetch array instead of objects from database when you use PDO.

PHP object use less memory and same fast or even faster then arrays.

Introduction
============
In many projects we use arrays to manipulate data that we fetch from database. In most cases we follow this approach, because of:
1. Assumption that arrays are faster when we are manipulating data
2. Database drivers by default return arrays
3. Data mapping into objects usually connected with bad performance

Using arrays lead to different issues that affects application maintainability:
1. Luck of type hinting that usualy affects code readability and may introduce bugs
2. Arrays structure may vary that usualy lead to 
    a. error, because of missing array key
    b. structure unpredictability and inconsistency
3. Code duplication

Above mentioned cons can be avoided by using objects without significant performance degradation or even improvement. Under performence we understood low utilization of computing resources like memory and CPU time.

Below I'm providing facts that prove viability of using objects instead of arrays for data with predetermined structure. Under term data with predetermined structure we must mean data that have well know structure before fetching from any source(e.g. fetching from database).


Methodology
===========
PHP version 7.0.*

We use common production PHP configuration:
1. XDebug extension is unloaded
2. Enabled OpCache for CLI to use benefits of OpCache binary code optimizations

Also disabled GC to avoid excessive CPU usage during GC cycle.

We compare mode of time execution.

Benchmarks implemented using [PhpBench](https://github.com/phpbench/phpbench) framework.

TODO: specify environment  

Installation
============
```bash
composer install
```

Run benchmarks
==============
1. Object vs Array comparision 
```bash
PATH=$PATH:$(pwd)/bin vendor/bin/phpbench run --report=default --group=array
```
2. PDO fetch Object vs Array comparision 
```bash
PATH=$PATH:$(pwd)/bin vendor/bin/phpbench run --report=default --group=fetch
```
3. PDO fetchAll Object vs Array comparision 
```bash
PATH=$PATH:$(pwd)/bin vendor/bin/phpbench run --report=default --group=fetchAll
``` 

Results
===========
1. Object vs Array comparision
```text
+--------------------+-----------------------------+--------+--------+------+-----+------------+-----------+-----------+-----------+------------+-----------+--------+---------+
| benchmark          | subject                     | groups | params | revs | its | mem_peak   | best      | mean      | mode      | worst      | stdev     | rstdev | diff    |
+--------------------+-----------------------------+--------+--------+------+-----+------------+-----------+-----------+-----------+------------+-----------+--------+---------+
| ObjectVsArrayBench | benchArray                  | array  | []     | 1    | 20  | 5,100,704b | 6.20500ms | 6.79175ms | 6.56715ms | 8.64000ms  | 0.58301ms | 8.58%  | +18.38% |
| ObjectVsArrayBench | benchObject                 | array  | []     | 1    | 20  | 2,583,208b | 5.28200ms | 5.78455ms | 5.54759ms | 9.66000ms  | 0.92071ms | 15.92% | 0.00%   |
| ObjectVsArrayBench | benchObjectCollection       | array  | []     | 1    | 20  | 2,583,352b | 5.59100ms | 5.84890ms | 5.77007ms | 6.40800ms  | 0.22684ms | 3.88%  | +4.01%  |
| ObjectVsArrayBench | benchObjectSetters          | array  | []     | 1    | 20  | 2,583,224b | 9.07700ms | 9.47390ms | 9.35168ms | 10.19100ms | 0.28651ms | 3.02%  | +68.57% |
| ObjectVsArrayBench | benchObjectSettersConstruct | array  | []     | 1    | 20  | 2,583,240b | 8.77900ms | 9.11710ms | 8.93893ms | 11.11400ms | 0.52900ms | 5.80%  | +61.13% |
+--------------------+-----------------------------+--------+--------+------+-----+------------+-----------+-----------+-----------+------------+-----------+--------+---------+
```
2. PDO fetch Object vs Array comparision
```text
+----------------------------+------------------+--------+--------+------+-----+------------+------------+------------+------------+------------+-----------+--------+--------+
| benchmark                  | subject          | groups | params | revs | its | mem_peak   | best       | mean       | mode       | worst      | stdev     | rstdev | diff   |
+----------------------------+------------------+--------+--------+------+-----+------------+------------+------------+------------+------------+-----------+--------+--------+
| PdoFetchObjectVsArrayBench | benchArrayFetch  | fetch  | []     | 1    | 20  | 6,779,608b | 20.94600ms | 23.47705ms | 22.49166ms | 41.73100ms | 4.27102ms | 18.19% | 0.00%  |
| PdoFetchObjectVsArrayBench | benchObjectFetch | fetch  | []     | 1    | 20  | 4,422,504b | 23.75700ms | 25.04945ms | 24.42228ms | 27.71000ms | 1.06892ms | 4.27%  | +8.58% |
+----------------------------+------------------+--------+--------+------+-----+------------+------------+------------+------------+------------+-----------+--------+--------+
```
3. PDO fetchAll Object vs Array comparision
```text
+----------------------------+---------------------+----------+--------+------+-----+------------+------------+------------+------------+------------+-----------+--------+--------+
| benchmark                  | subject             | groups   | params | revs | its | mem_peak   | best       | mean       | mode       | worst      | stdev     | rstdev | diff   |
+----------------------------+---------------------+----------+--------+------+-----+------------+------------+------------+------------+------------+-----------+--------+--------+
| PdoFetchObjectVsArrayBench | benchArrayFetchAll  | fetchAll | []     | 1    | 20  | 6,779,680b | 17.85000ms | 19.64885ms | 18.82402ms | 31.30200ms | 2.84138ms | 14.46% | 0.00%  |
| PdoFetchObjectVsArrayBench | benchObjectFetchAll | fetchAll | []     | 1    | 20  | 4,422,560b | 19.54100ms | 22.40240ms | 20.49579ms | 40.75400ms | 5.75372ms | 25.68% | +8.88% |
+----------------------------+---------------------+----------+--------+------+-----+------------+------------+------------+------------+------------+-----------+--------+--------+
```

Conclusions
===========
TODO: describe conclusions about possible object initialization using public property, setters, constructor

References
==========
1. "...immutable arrays are only used if opcache is enabled..." 
https://nikic.github.io/2015/06/19/Internal-value-representation-in-PHP-7-part-2.html#arrays
2. Comparing PHP v5.3 and v5.4 CPU time and memory usage between classes and arrays
https://gist.github.com/patrickallaert/5015153
3. Why objects (usually) use less memory than arrays in PHP
https://gist.github.com/nikic/5015323
4. "...PHP doesn't automatically copy an array when it is passed to a function..."
https://gist.github.com/nikic/5015323#gistcomment-1340650
