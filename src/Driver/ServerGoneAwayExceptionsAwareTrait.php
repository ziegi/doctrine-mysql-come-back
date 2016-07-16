<?php

namespace Facile\DoctrineMySQLComeBack\Doctrine\DBAL\Driver;

/**
 * Trait ServerGoneAwayExceptionsAwareTrait.
 */
trait ServerGoneAwayExceptionsAwareTrait
{
    /**
     * @var array
     */
    protected $goneAwayExceptions = array(
        2002, // => 'Can\'t connect to local MySQL server through socket',
        2003, // => 'Can\'t connect to MySQL server on',
        2005, // => 'Unknown MySQL server host',
        2006, // => 'MySQL server has gone away',
        2013, // => 'Lost connection to MySQL server during query',
        2055, // => 'Lost connection to MySQL server at',
    );

    /**
     * @var array
     */
    protected $goneAwayInUpdateExceptions = array(
        2006, // => 'MySQL server has gone away',
        2013, // => 'Lost connection to MySQL server during query',
    );

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    public function isGoneAwayException(\Exception $exception)
    {
        $message = $exception->getMessage();

        foreach ($this->goneAwayExceptions as $exceptionCode) {
            if ($exceptionCode === $errorcode) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    public function isGoneAwayInUpdateException(\Exception $exception)
    {
        $message = $exception->getMessage();

        foreach ($this->goneAwayInUpdateExceptions as $exceptionCode) {
            if ($exceptionCode === $errorcode) {
                return true;
            }
        }

        return false;
    }
}
