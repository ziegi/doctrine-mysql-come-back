<?php

namespace Facile\DoctrineMySQLComeBack\Doctrine\DBAL;

use PDO;
use Doctrine\DBAL\Driver\Statement as DriverStatement;

/**
 * Class Statement.
 */
class Statement implements \IteratorAggregate, DriverStatement
{
    /**
     * @var string
     */
    protected $sql;
    /**
     * @var \Doctrine\DBAL\Statement
     */
    protected $stmt;
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @param $sql
     * @param Connection $conn
     */
    public function __construct($sql, Connection $conn)
    {
        $this->sql = $sql;
        $this->conn = $conn;
        $this->createStatement();
    }

    /**
     * Create Statement.
     */
    private function createStatement()
    {
        $this->stmt = $this->conn->prepareUnwrapped($this->sql);
    }

    /**
     * @param array|null $params
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function execute($params = null)
    {
        $stmt = null;
        $attempt = 0;
        $retry = true;
        while ($retry) {
            $retry = false;
            try {
                $stmt = $this->stmt->execute($params);
            } catch (\Exception $e) {
                if ($this->conn->canTryAgain($attempt) && $this->conn->isRetryableException($e, $this->sql)) {
                    $this->conn->close();
                    $this->createStatement();
                    ++$attempt;
                    $retry = true;
                    sleep($this->conn->getSecondsBeforeRetry());
                } else {
                    throw $e;
                }
            }
        }

        return $stmt;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param mixed  $type
     *
     * @return bool
     */
    public function bindValue($name, $value, $type = null)
    {
        return $this->stmt->bindValue($name, $value, $type);
    }

    /**
     * @param string   $name
     * @param mixed    $var
     * @param int      $type
     * @param int|null $length
     *
     * @return bool
     */
    public function bindParam($name, &$var, $type = PDO::PARAM_STR, $length = null)
    {
        return $this->stmt->bindParam($name, $var, $type, $length);
    }

    /**
     * @return bool
     */
    public function closeCursor()
    {
        return $this->stmt->closeCursor();
    }

    /**
     * @return int
     */
    public function columnCount()
    {
        return $this->stmt->columnCount();
    }

    /**
     * @return int
     */
    public function errorCode()
    {
        return $this->stmt->errorCode();
    }

    /**
     * @return array
     */
    public function errorInfo()
    {
        return $this->stmt->errorInfo();
    }

    /**
     * @param int   $fetchMode
     * @param mixed $arg2
     * @param mixed $arg3
     *
     * @return bool
     */
    public function setFetchMode($fetchMode, $arg2 = null, $arg3 = null)
    {
        return $this->stmt->setFetchMode($fetchMode, $arg2, $arg3);
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->stmt;
    }

    /**
     * @param int|null $fetchMode
     *
     * @return mixed
     */
    public function fetch($fetchMode = null)
    {
        return $this->stmt->fetch($fetchMode);
    }

    /**
     * @param int|null $fetchMode
     * @param int      $fetchArgument
     *
     * @return mixed
     */
    public function fetchAll($fetchMode = null, $fetchArgument = 0)
    {
        return $this->stmt->fetchAll($fetchMode, $fetchArgument);
    }

    /**
     * @param int $columnIndex
     *
     * @return mixed
     */
    public function fetchColumn($columnIndex = 0)
    {
        return $this->stmt->fetchColumn($columnIndex);
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * @return \Doctrine\DBAL\Statement
     */
    public function getWrappedStatement()
    {
        return $this->stmt;
    }
}
