<?php

namespace phpcodex\FTDB\DataObjects;

use \IteratorAggregate;
use \ArrayIterator;

/**
 * Class FTDBData
 *
 * A DataObjects class which will provide a Key Value Pair
 * Object to the data we are wanting access to.
 *
 * @category Unknown
 * @package  App\FTDB\DataObjects
 * @author   Richard Dickinson <richard@imleeds.com>
 * @license  Creative Commons https://creativecommons.org/licenses/by/4.0/
 * @link     http://www.imleeds.com
 */
class FTDBData implements IteratorAggregate
{
    /**
     * Get Iterator.
     *
     * Allow our object to be traversable.
     *
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this);
    }
}