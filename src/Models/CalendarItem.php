<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;

class CalendarItem extends Model
{
    protected $endpoint = 'Planner/GetCalendarItem';

    protected $required_parameters = ['date'];
    protected $default_parameters = [];
    protected $available_parameters = [
        'id', // The Calendar ID
        'date',  // Date in YYYY-MM-DD format
        'days',  // number of days to search
        'employeeid', //  ID of the trainer
    ];
}
