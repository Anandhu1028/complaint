<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'reference_no','full_name','mobile','email','address','state','district',
        'subject','details','attachment_path','status','email_sent_at','email_error'
    ];

    protected $casts = ['email_sent_at' => 'datetime'];
}
