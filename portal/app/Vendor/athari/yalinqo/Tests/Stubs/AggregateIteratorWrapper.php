<?php

namespace Tests\Stubs;

class AggregateIteratorWrapper implements \IteratorAggregate
{
    private $iterator;

    /**
     * @param \Iterator $iterator
     */
    public function __construct ($iterator)
    {
        $this->iterator = $iterator;
    }

    public function getIterator ()
    {
        return $this->iterator;
    }
}
