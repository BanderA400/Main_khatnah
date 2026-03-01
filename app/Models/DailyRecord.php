<?php

namespace App\Models;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyRecord extends Model
{
    protected $fillable = [
        'khatma_id',
        'user_id',
        'date',
        'from_page',
        'to_page',
        'pages_count',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'from_page' => 'integer',
        'to_page' => 'integer',
        'pages_count' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $record): void {
            if (!$record->khatma_id) {
                throw new InvalidArgumentException('رقم الختمة مطلوب لإنشاء سجل الإنجاز.');
            }

            $khatma = Khatma::query()
                ->select('id', 'user_id')
                ->find($record->khatma_id);

            if (!$khatma) {
                throw new InvalidArgumentException('الختمة المرتبطة بسجل الإنجاز غير موجودة.');
            }

            if ($record->user_id !== null && (int) $record->user_id !== (int) $khatma->user_id) {
                throw new InvalidArgumentException('المستخدم لا يطابق صاحب الختمة.');
            }

            $record->user_id = $khatma->user_id;
        });
    }

    // ==================
    // العلاقات
    // ==================

    public function khatma(): BelongsTo
    {
        return $this->belongsTo(Khatma::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
