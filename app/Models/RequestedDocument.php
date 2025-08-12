<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedDocument extends Model
{
    use HasFactory;

    protected $table = 'requested_documents';
    protected $fillable = [
        'request_id', 'document_type', 'quantity'
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class, 'request_id');
    }
}
