<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HolidayService
{
    /**
     * Fetch holidays for a specific year and country.
     * Including a robust fallback for India 2026.
     */
    public static function getHolidays($year = 2026, $countryCode = 'IN')
    {
        return Cache::remember("holidays_{$year}_{$countryCode}", 86400, function () use ($year) {
            // Priority: Holidays 2026 (Source: Comprehensive User List)
            $holidays = [
                ['date' => '2026-01-01', 'name' => "New Year's Day"],
                ['date' => '2026-01-03', 'name' => "Hazarat Ali's Birthday"],
                ['date' => '2026-01-14', 'name' => "Pongal"],
                ['date' => '2026-01-14', 'name' => "Makar Sankranti"],
                ['date' => '2026-01-23', 'name' => "Vasant Panchami"],
                ['date' => '2026-01-26', 'name' => "Republic Day"],
                ['date' => '2026-02-14', 'name' => "Valentine's Day"],
                ['date' => '2026-02-15', 'name' => "Maha Shivaratri"],
                ['date' => '2026-02-17', 'name' => "Lunar New Year"],
                ['date' => '2026-02-19', 'name' => "Ramadan Start"],
                ['date' => '2026-02-19', 'name' => "Shivaji Jayanti"],
                ['date' => '2026-03-04', 'name' => "Holi"],
                ['date' => '2026-03-19', 'name' => "Ugadi"],
                ['date' => '2026-03-19', 'name' => "Gudi Padwa"],
                ['date' => '2026-03-20', 'name' => "March Equinox"],
                ['date' => '2026-03-21', 'name' => "Ramzan Id/Eid-ul-Fitar"],
                ['date' => '2026-03-26', 'name' => "Rama Navami"],
                ['date' => '2026-04-02', 'name' => "First day of Passover"],
                ['date' => '2026-04-02', 'name' => "Maundy Thursday"],
                ['date' => '2026-04-03', 'name' => "Good Friday"],
                ['date' => '2026-04-05', 'name' => "Easter Day"],
                ['date' => '2026-04-14', 'name' => "Ambedkar Jayanti"],
                ['date' => '2026-05-01', 'name' => "International Worker's Day"],
                ['date' => '2026-05-10', 'name' => "Mothers' Day"],
                ['date' => '2026-05-28', 'name' => "Bakrid/Eid ul-Adha"],
                ['date' => '2026-06-21', 'name' => "Fathers' Day"],
                ['date' => '2026-06-21', 'name' => "June Solstice"],
                ['date' => '2026-06-26', 'name' => "Muharram/Ashura"],
                ['date' => '2026-07-16', 'name' => "Rath Yatra"],
                ['date' => '2026-08-02', 'name' => "Friendship Day"],
                ['date' => '2026-08-15', 'name' => "Independence Day"],
                ['date' => '2026-08-26', 'name' => "Onam"],
                ['date' => '2026-08-28', 'name' => "Raksha Bandhan (Rakhi)"],
                ['date' => '2026-09-04', 'name' => "Janmashtami"],
                ['date' => '2026-09-14', 'name' => "Ganesh Chaturthi"],
                ['date' => '2026-09-23', 'name' => "September Equinox"],
                ['date' => '2026-10-02', 'name' => "Mahatma Gandhi Jayanti"],
                ['date' => '2026-10-11', 'name' => "First Day of Sharad Navratri"],
                ['date' => '2026-10-17', 'name' => "First Day of Durga Puja"],
                ['date' => '2026-10-20', 'name' => "Dussehra"],
                ['date' => '2026-10-29', 'name' => "Karva Chauth"],
                ['date' => '2026-10-31', 'name' => "Halloween"],
                ['date' => '2026-11-08', 'name' => "Diwali"],
                ['date' => '2026-11-11', 'name' => "Bhai Duj"],
                ['date' => '2026-11-15', 'name' => "Chhat Puja"],
                ['date' => '2026-11-24', 'name' => "Guru Tegh Bahadur's Martyrdom Day"],
                ['date' => '2026-12-05', 'name' => "First Day of Hanukkah"],
                ['date' => '2026-12-12', 'name' => "Last day of Hanukkah"],
                ['date' => '2026-12-22', 'name' => "December Solstice"],
                ['date' => '2026-12-23', 'name' => "Hazarat Ali's Birthday"],
                ['date' => '2026-12-24', 'name' => "Christmas Eve"],
                ['date' => '2026-12-25', 'name' => "Christmas"],
                ['date' => '2026-12-31', 'name' => "New Year's Eve"],
            ];

            // Generate Sundays for the entire year
            $startDate = Carbon::createFromDate($year, 1, 1);
            $endDate = Carbon::createFromDate($year, 12, 31);

            while ($startDate->lte($endDate)) {
                if ($startDate->isSunday()) {
                    $holidays[] = [
                        'date' => $startDate->format('Y-m-d'),
                        'name' => 'Sunday Holiday'
                    ];
                }
                $startDate->addDay();
            }

            // Sort holidays by date
            usort($holidays, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            return $holidays;
        });
    }

    /**
     * Get only upcoming holidays.
     */
    public static function getUpcoming($limit = 5)
    {
        $holidays = self::getHolidays(2026, 'IN');

        return collect($holidays)->filter(function ($h) {
            return Carbon::parse($h['date'])->isFuture();
        })->take($limit);
    }

    /**
     * Get holidays for the current month.
     */
    public static function getCurrentMonthHolidays()
    {
        $holidays = self::getHolidays(2026, 'IN');
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return collect($holidays)->filter(function ($h) use ($startOfMonth, $endOfMonth) {
            $date = Carbon::parse($h['date']);
            return $date->between($startOfMonth, $endOfMonth);
        });
    }
}
