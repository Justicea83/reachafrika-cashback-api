<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MethodsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_number_is_between()
    {
        $this->assertTrue(true, $this->isBetween(5,8,4,9));
        $this->assertTrue(true, $this->isBetween(5,8,6,10));
        $this->assertTrue(true, $this->isBetween(4,7,8,10));
    }

    private function isBetween($start,$end,$searchStart,$searchEnd): bool{
        return ($searchStart >= $start && $searchStart <= $end)
            ||
            ($searchEnd >= $start && $searchEnd <= $end)
            ||
            ($start >= $searchStart && $start <= $searchEnd)
            ||
            ($end >= $searchStart && $end <= $searchEnd)
            ;
    }
}
