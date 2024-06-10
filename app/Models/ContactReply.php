<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    protected   $table = 'contacts_reply';

    protected $fillable = ['contact_id', 'mail_form', 'content'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
