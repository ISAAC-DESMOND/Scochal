<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class direct_msg extends Model
{

    /** @use HasFactory<\Database\Factories\DirectMsgFactory> */
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'message',
    ];

    /**
     * Define the sender relationship.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Define the recipient relationship.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public static function last($recipient_id)
    {
        $user_id=Auth::id();
        $last_msg=direct_msg::where(function ($query) use ($user_id, $recipient_id) {
            $query->where('sender_id', $user_id)
                  ->where('recipient_id', $recipient_id);
        })->orWhere(function ($query) use ($user_id, $recipient_id) {
            $query->where('sender_id', $recipient_id)
                  ->where('recipient_id', $user_id);})->orderBy('created_at', 'desc')->get()->first();

            return $last_msg->message;

    }
}
