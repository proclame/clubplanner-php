<?php

namespace Proclame\Clubplanner;

use Proclame\Clubplanner\Connection;
use Proclame\Clubplanner\Models\Club;
use Proclame\Clubplanner\Models\Member;
use Proclame\Clubplanner\Models\MemberSubscription;
use Proclame\Clubplanner\Models\Status;
use Proclame\Clubplanner\Models\Calendar;
use Proclame\Clubplanner\Models\Employee;
use Proclame\Clubplanner\Models\Checkinpoint;
use Proclame\Clubplanner\Exceptions\ClubplannerException;
use Proclame\Clubplanner\Models\CalendarItem;
use Proclame\Clubplanner\Models\Parameter;
use Proclame\Clubplanner\Models\PaymentMethod;
use Proclame\Clubplanner\Models\Subscription;
use Proclame\Clubplanner\Models\SubscriptionGroup;

/**
 * Class Clubplanner
 *
 * The main class for API consumption
 *
 * @package Proclame\Clubplanner
 */
class Clubplanner
{
    protected $connection;

    /**
     * @param string|null $token The API access token, as obtained directly from clubplanner
     * @throws ClubplannerException When no token is provided
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function member($attributes = [])
    {
        if (is_int($attributes) || (is_string($attributes) && is_numeric($attributes))) {
            return new Member($this->connection, ['id' => $attributes]);
        }
        return new Member($this->connection, $attributes);
    }

    public function member_subscription($attributes = [])
    {
        return new MemberSubscription($this->connection, $attributes);
    }
    public function employee($attributes = [])
    {
        return new Employee($this->connection, $attributes);
    }
    public function status($attributes = [])
    {
        return new Status($this->connection, $attributes);
    }
    public function calendar($attributes = [])
    {
        return new Calendar($this->connection, $attributes);
    }
    public function calendar_item($attributes = [])
    {
        return new CalendarItem($this->connection, $attributes);
    }
    public function Club($attributes = [])
    {
        return new Club($this->connection, $attributes);
    }
    public function checkin_point($attributes = [])
    {
        return new Checkinpoint($this->connection, $attributes);
    }
    public function payment_method($attributes = [])
    {
        return new PaymentMethod($this->connection, $attributes);
    }
    public function subscription($attributes = [])
    {
        return new Subscription($this->connection, $attributes);
    }
    public function subscription_group($attributes = [])
    {
        return new SubscriptionGroup($this->connection, $attributes);
    }
    public function parameter($attributes = [])
    {
        return new Parameter($this->connection, $attributes);
    }

    public function wakeup()
    {
        return $this->connection->get('home/wakeup');
    }
}
