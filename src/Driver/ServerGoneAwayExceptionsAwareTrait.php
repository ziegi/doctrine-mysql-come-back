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
    protected $goneAwayExceptionsMysql = array(
        2002,   // 'Can\'t connect to local MySQL server through socket'
        2003,   // 'Can\'t connect to MySQL server on'
        2005,   // 'Unknown MySQL server host'
        2006,   // 'MySQL server has gone away'
        2013,   // 'Lost connection to MySQL server during query'
        2055,   // 'Lost connection to MySQL server at'
    );

    /**
     * @var array
     */
    protected $goneAwayInUpdateExceptionsMysql = array(
        2006,   // 'MySQL server has gone away'
        2013,   // 'Lost connection to MySQL server during query'
    );

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    public function isGoneAwayException(\Exception $exception)
    {
        $message = $exception->getMessage();
        $errorcode = $exception->getErrorCode();

        // all relevant exceptions implement the getErrorCode() function
        // while the getCode() might deliver similar results for other exceptions i do not care about them here
        if (!method_exists($exception, 'getErrorCode'))
            return false;
        $errorcode = $exception->getErrorCode();

        foreach ($this->goneAwayExceptionsMysql as $exceptionCode) {
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

        // all relevant exceptions implement the getErrorCode() function
        // while the getCode() might deliver similar results for other exceptions i do not care about them here
        if (!method_exists($exception, 'getErrorCode'))
            return false;
        $errorcode = $exception->getErrorCode();
        
        foreach ($this->goneAwayInUpdateExceptionsMysql as $exceptionCode) {
            if ($exceptionCode === $errorcode) {
                return true;
            }
        }

        return false;
    }
}
