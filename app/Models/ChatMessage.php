<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_message';
    protected $fillable = ['room_id', 'uid', 'from_id', 'message', 'message_type', 'status', 'timestamp', 'extra_field'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
}
