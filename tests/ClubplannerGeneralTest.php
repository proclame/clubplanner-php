<?php

namespace Proclame\Clubplanner\Test;

use PHPUnit\Framework\TestCase;
use Proclame\Clubplanner\Connection;
use Proclame\Clubplanner\Clubplanner;

/**
 * @runTestsInSeparateProcesses
 */
class ClubplannerGeneralTest extends TestCase
{
    protected $connection;
    protected $clubplanner;

    protected function setUp(): void
    {
        $this->connection = new Connection();
        $this->connection->setApiKey($_ENV['CLUBPLANNER_TOKEN']);
        $this->connection->setApiUrl($_ENV['CLUBPLANNER_URL']);
        $this->clubplanner = new Clubplanner($this->connection);
    }

    public function testWakeUpReturnsOkResult()
    {
        $result = $this->clubplanner->wakeup();
        $this->assertEquals("OK", $result['Status']);
    }
}
