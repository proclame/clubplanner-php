<?php

namespace Proclame\Clubplanner\Test;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Proclame\Clubplanner\Connection;
use Proclame\Clubplanner\Clubplanner;
use Proclame\Clubplanner\Models\CalendarItem;
use Proclame\Clubplanner\Exceptions\ClubplannerException;

/**
 * @runTestsInSeparateProcesses
 */
class ClubplannerCalendarItemTest extends TestCase
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

    public function testGetCalendarItemsShouldReturnArrayOfCalendarItems()
    {
        $calendar_items = $this->clubplanner->calendar_item()->get(['date' => '2020-10-08', 'days' => 7]);
        $this->assertIsArray($calendar_items);
        $this->assertInstanceOf(CalendarItem::class, $calendar_items[0]);
    }
    public function testAddCalendarItemShouldAddTheCalendarItem()
    {
        $this->markTestSkipped();
    }
    public function testUpdateCalendarItemShouldUpdateTheCalendarItem()
    {
        $this->markTestSkipped();
    }
}
