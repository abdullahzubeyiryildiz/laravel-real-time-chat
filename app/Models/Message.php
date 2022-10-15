<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";

    protected $fillable = ['message', 'sent_to_id', 'sender_id', 'read_at', 'status'];

    protected $guard = "id";

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'sent_to_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }


    public function replaysViewCheck()
    {
        return $this->sender()->where('read_at', '1');
    }

    public function replaysNotViewCheck()
    {
        return $this->sender()->where('read_at', '0');
    }

    public function scopeSentToUser($query, $user_id = null)
    {
        if (!$user_id && !Auth::check()) {
            throw new \InvalidArgumentException('User ID is undefined');
        } else {
            $user_id = Auth::id();
        }
        return $query->whereHas('receiver', function ($sql) use ($user_id) {
            $sql->where('id', $user_id);
        });
    }

}
