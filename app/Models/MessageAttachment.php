<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model {
    protected $primaryKey = 'id_attachment';
    protected $fillable = ['id_message','filename','path','mime','size'];

    public function message() {
        return $this->belongsTo(Message::class, 'id_message');
    }
}
