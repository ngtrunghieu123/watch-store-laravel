<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    use HasFactory;

    protected $table = 'chatbot_messages';

    protected $fillable = [
        'nguoidung_id',
        'session_id',
        'message',
        'is_bot'
    ];

    public function nguoidung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoidung_id', 'id');
    }
}
