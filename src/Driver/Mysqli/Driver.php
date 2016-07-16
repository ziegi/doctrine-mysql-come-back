<?php

namespace Facile\DoctrineMySQLComeBack\Doctrine\DBAL\Driver\Mysqli;

use Facile\DoctrineMySQLComeBack\Doctrine\DBAL\Driver\ServerGoneAwayExceptionsAwareInterface;
use Facile\DoctrineMySQLComeBack\Doctrine\DBAL\Driver\ServerGoneAwayExceptionsAwareTrait;

/**
 * Class Driver.
 */
class Driver extends \Doctrine\DBAL\Driver\Mysqli\Driver implements ServerGoneAwayExceptionsAwareInterface
{
    use ServerGoneAwayExceptionsAwareTrait;

    /**
     * @var array
     */
    private $extendedDriverOptions = [
        'x_reconnect_attempts',
        'x_seconds_before_retry',
    ];

    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        $driverOptions = array_diff_key($driverOptions, array_flip($this->extendedDriverOptions));

        return parent::connect($params, $username, $password, $driverOptions);
    }
}
