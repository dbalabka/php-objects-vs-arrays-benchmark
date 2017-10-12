<?php

namespace Php\Bench\ArrayFunc;

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

/**
 * @BeforeMethods({"setConnection"})
 * @AfterMethods({"onAfterMethods"})
 * @Iterations(20)
 * @Revs(1)
 * @OutputTimeUnit("milliseconds", precision=5)
 * @Sleep(1000)
 * @BeforeClassMethods({"initDatabase"})
 */
class PdoFetchObjectVsArrayBench
{
    const QUERY = 'SELECT * FROM messages';
    const ROW_COUNT = 10000;

    /**
     * @var \PDO
     */
    protected $db;

    public function setConnection()
    {
        $this->db = new \PDO('sqlite:messaging.sqlite3');
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_PREFETCH, 10000);
    }

    public function onAfterMethods()
    {

    }

    /**
     * @Groups({"fetch"})
     */
    public function benchArrayFetch()
    {
        $stmt = $this->db->query(self::QUERY);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $rows = [];
        while ($row = $stmt->fetch()) {
            $rows[] = $row;
        }
        count($rows);
    }

    /**
     * @Groups({"fetchAll"})
     */
    public function benchArrayFetchAll()
    {
        $stmt = $this->db->query(self::QUERY);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        count($rows);
    }

    /**
     * @Groups({"fetch"})
     */
    public function benchObjectFetch()
    {
        $stmt = $this->db->query(self::QUERY);
        $stmt->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, Message::class);
        $rows = [];
        while ($row = $stmt->fetch()) {
            $rows[] = $row;
        }
        count($rows);
    }

    /**
     * @Groups({"fetchAll"})
     */
    public function benchObjectFetchAll()
    {
        $stmt = $this->db->query(self::QUERY);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Message::class);
        $rows = $stmt->fetchAll(\PDO::FETCH_CLASS, Message::class);
        count($rows);
    }

    public static function initDatabase()
    {
        $dbPrepare = new \PDO('sqlite:messaging.sqlite3');
        $dbPrepare->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $dbPrepare->exec('DROP TABLE IF EXISTS messages');
        $dbPrepare->exec("CREATE TABLE messages (
                      id INTEGER PRIMARY KEY, 
                      title TEXT, 
                      message TEXT, 
                      time TEXT)");

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO messages (title, message, time) 
                VALUES (:title, :message, :time)";
        $stmt = $dbPrepare->prepare($insert);

        // Bind parameters to statement variables
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':time', $time);

        for ($i = 0; $i < self::ROW_COUNT; $i++) {
            // Set values to bound variables
            $title = 'Title' . md5(rand(1, 1000));
            $message = 'Message' . md5(rand(1, 1000));
            $time = rand(1, 1000000000);
            // Execute statement
            $stmt->execute();
        }
    }
}

/**
 * NOTE! Constructor of this class will be called before properties mapping
 */
class Message {
    private $id = 0;
    private $title;
    private $message;
    private $time;

    public function __set($name, $value)
    {
        if (!property_exists(__CLASS__, $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid property: %s', $name));
        }
    }
}