<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcrLog extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'filename',
        'raw_text',
        'parsed_data',
        'status',
        'error_message',
    ];

    protected $casts = [
        'parsed_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
