<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class MessageTemplate extends Model
{
    protected $fillable = [
        'message_id', 'version', 'subject', 'template', 'parent_id', 'is_active'
    ];

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query) {
        return $query->whereNull('parent_id');
    }

    public function message() {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function parentTemplate() {
        return $this->belongsTo(MessageTemplate::class, 'parent_id');
    }

    public function scopeSiblings($query, $messageId) {
        return $query->where('message_id', $messageId);
    }
}
