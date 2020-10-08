<?php

namespace Proclame\Clubplanner\Test;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Proclame\Clubplanner\Connection;
use Proclame\Clubplanner\Clubplanner;
use Proclame\Clubplanner\Models\Calendar;
use Proclame\Clubplanner\Exceptions\ClubplannerException;

/**
 * @runTestsInSeparateProcesses
 */
class ClubplannerCalendarTest extends TestCase
{
    protected $connection;
    protected $clubplanner;

    protected $faker;

    protected function setUp(): void
    {
        $this->connection = new Connection();
        $this->connection->setApiKey($_ENV['CLUBPLANNER_TOKEN']);
        $this->connection->setApiUrl($_ENV['CLUBPLANNER_URL']);
        $this->connection->connect();
        $this->faker = Factory::create('nl_BE');
        $this->clubplanner = new Clubplanner($this->connection);
    }

    public function testGetCalendarsShouldReturnArrayOfCalendars()
    {
        $calendars = $this->clubplanner->calendar()->get();
        $this->assertIsArray($calendars);
        $this->assertInstanceOf(Calendar::class, $calendars[0]);
    }
}
