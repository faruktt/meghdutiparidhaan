<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'session_id',
        'page_url',
        'page_title',
        'device_type',
        'browser',
        'platform',
        'referrer',
        'visit_date',
    ];

    /**
     * Record a frontend visit.
     */
    public static function recordVisit(Request $request): ?self
    {
        $ip = $request->ip();
        $sessionId = session()->getId();
        $url = $request->fullUrl();
        $today = Carbon::today()->toDateString();

        // Avoid logging rapid duplicate requests from the same session to the same URL within 5 minutes
        $recent = self::where('session_id', $sessionId)
            ->where('page_url', $url)
            ->where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->exists();

        if ($recent) {
            return null;
        }

        $userAgent = $request->userAgent() ?? '';

        // Device detection
        $deviceType = 'Desktop';
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            $deviceType = 'Tablet';
        } elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile|mobile)/i', $userAgent)) {
            $deviceType = 'Mobile';
        }

        // Platform detection
        $platform = 'Other';
        if (preg_match('/windows|win32/i', $userAgent)) $platform = 'Windows';
        elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) $platform = 'iOS';
        elseif (preg_match('/android/i', $userAgent)) $platform = 'Android';
        elseif (preg_match('/macintosh|mac os x/i', $userAgent)) $platform = 'Mac OS';
        elseif (preg_match('/linux/i', $userAgent)) $platform = 'Linux';

        // Browser detection
        $browser = 'Other';
        if (preg_match('/edg/i', $userAgent)) $browser = 'Edge';
        elseif (preg_match('/chrome|crios/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/firefox|fxios/i', $userAgent)) $browser = 'Firefox';
        elseif (preg_match('/safari/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/opera|opr/i', $userAgent)) $browser = 'Opera';

        return self::create([
            'ip_address'  => $ip,
            'session_id'  => $sessionId,
            'page_url'    => substr($url, 0, 500),
            'page_title'  => null,
            'device_type' => $deviceType,
            'browser'     => $browser,
            'platform'    => $platform,
            'referrer'    => substr($request->headers->get('referer', ''), 0, 500) ?: null,
            'visit_date'  => $today,
        ]);
    }

    /**
     * Analytics Query Helpers
     */
    public static function totalUniqueVisitors(): int
    {
        return (int) self::distinct('ip_address')->count('ip_address');
    }

    public static function todayUniqueVisitors(): int
    {
        return (int) self::where('visit_date', Carbon::today()->toDateString())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    public static function yesterdayUniqueVisitors(): int
    {
        return (int) self::where('visit_date', Carbon::yesterday()->toDateString())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    public static function thisMonthUniqueVisitors(): int
    {
        return (int) self::where('visit_date', '>=', Carbon::now()->startOfMonth()->toDateString())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    public static function totalPageViews(): int
    {
        return (int) self::count();
    }

    public static function todayPageViews(): int
    {
        return (int) self::where('visit_date', Carbon::today()->toDateString())->count();
    }

    /**
     * Daily breakdown stats for date-wise reporting.
     */
    public static function dailyStats(int $days = 30)
    {
        return self::selectRaw('
            visit_date, 
            COUNT(DISTINCT ip_address) as unique_visitors, 
            COUNT(*) as page_views,
            SUM(CASE WHEN device_type = "Mobile" THEN 1 ELSE 0 END) as mobile_views,
            SUM(CASE WHEN device_type = "Desktop" THEN 1 ELSE 0 END) as desktop_views
        ')
        ->groupBy('visit_date')
        ->orderBy('visit_date', 'desc')
        ->take($days)
        ->get();
    }
}

