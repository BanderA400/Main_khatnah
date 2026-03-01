<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    protected $fillable = [
        'number',
        'name_arabic',
        'total_ayahs',
        'start_page',
        'end_page',
        'juz',
    ];

    protected $casts = [
        'number' => 'integer',
        'total_ayahs' => 'integer',
        'start_page' => 'integer',
        'end_page' => 'integer',
        'juz' => 'integer',
    ];

    /**
     * الحصول على السورة حسب رقم الصفحة
     */
    public static function getByPage(int $page): ?self
    {
        return static::where('start_page', '<=', $page)
            ->where('end_page', '>=', $page)
            ->first();
    }
}
