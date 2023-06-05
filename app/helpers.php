<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\User;

function inMaintenance()
{
    return File::exists(storage_path('/framework/down'));
}

function currentUser() {
  return auth()->user();
}

function getUser($id)
{
  return User::find($id);
}

function isAdmin()
{
    return currentUser()->roles()->pluck('name')->contains('admin');
}

function isSuperAdmin()
{
    return currentUser()->roles()->pluck('name')->contains('super-admin');
}

function makeTransactionId()
{
    $time = time();
    $four = strlen($time) - 4;
    return substr($time, 0, $four).'-'.substr($time,$four,4);
}

function breakDown($serialized)
{
    return implode(', ',unserialize($serialized));
}

function unSlugger($string)
{
  return ucwords(str_replace('-',' ', $string));
}

function dollars($cents)
{
   return '$' . number_format($cents / 100, 2);
}

function prorate($cents)
{
    $days_left = daysLeft();
    if($days_left == 0) $days_left = 1;
    $days_total = daysInMonth();
    $prorate = ceil(($cents / $days_total) * $days_left);
    $prorate = [
        'dollars' => dollars($prorate),
        'cents' => $prorate,
    ];
    return $prorate;
}

function formatDate($date)
{
    return Carbon::parse($date)->format('F d, Y');
}

function formatDateTime($date)
{
    return Carbon::parse($date)->format('F d, Y | g:i a');
}

function formatDateNumber($date)
{
    return Carbon::parse($date)->format('n/j/y');
}

function formatDateInput($date)
{
    return Carbon::parse($date)->format('Y-m-d');
}

function addDays($date, $days)
{
    return Carbon::parse($date)->addDays($days)->format('n/j/y');
}

function daysUntil($date)
{
    $date = Carbon::parse($date);
    return $date->diffForHumans(['parts' => 1, 'join' => ', ',]);
}

function daysLeft()
{
    $now = now();
    // $now = Carbon::parse('2021-6-28 23:59');
    $monthEnd = Carbon::parse($now)->endOfMonth();
    return $now->diffInDays($monthEnd);
}

function daysInMonth()
{
    $now = now();
    $monthStart = Carbon::parse($now)->startOfMonth();
    $monthEnd = Carbon::parse($now)->endOfMonth();
    return $monthStart->diffInDays($monthEnd) + 1;
}

function timedGreeting()
{
    $now = (int) date('Hi');
    $greeting = 'Good Morning';
    if($now >= 1200 && $now <= 1659) {
        $greeting = 'Good Afternoon';
    } elseif($now >= 1700 || $now <= 459) {
        $greeting = 'Good Evening';
    }
    return $greeting;
}
