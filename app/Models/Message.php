<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    protected $primaryKey = 'id_message';
    protected $fillable = ['id_chat','sender_user_id','sender_teknisi_id','message','type','is_read'];

    public function chat() {
        return $this->belongsTo(Chat::class, 'id_chat');
    }

    public function attachments() {
        return $this->hasMany(MessageAttachment::class, 'id_message');
    }

    public function getUrlAttribute()
    {
        return basename($this->path);
    }

}
