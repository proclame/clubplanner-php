<?php

namespace Proclame\Clubplanner\Test;

use PHPUnit\Framework\TestCase;
use Proclame\Clubplanner\Connection;
use Proclame\Clubplanner\Clubplanner;
use Proclame\Clubplanner\Models\Status;

/**
 * @runTestsInSeparateProcesses
 */
class ClubplannerStatusTest extends TestCase
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

    public function testGetStatussShouldReturnArrayOfStatuss()
    {
        $Statusses = $this->clubplanner->Status()->get();
        $this->assertIsArray($Statusses);
        $this->assertInstanceOf(Status::class, $Statusses[0]);
    }
}
