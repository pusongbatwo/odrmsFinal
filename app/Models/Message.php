<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_request_id',
        'sender_type',
        'sender_id',
        'message',
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}
