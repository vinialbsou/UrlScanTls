<?php

namespace App\Enumerations;

/*class StatusQueue extends Enumeration
{
    const Pending = 'Pending';
    const Scanning = 'Scanning';
    const Done = 'Done';
    const Error = 'Error';
} */
enum StatusQueue: string {
    case Pending = "Pending";
    case Scanning = "Scanning";
    case Done = "Done";
    case Error = "Error";
}
