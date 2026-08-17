<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';

    public $timestamps = false;

    protected $fillable = ['name', 'email', 'customer_id', 'message', 'admin_reply', 'replied_at'];

    public const FACEBOOK_URL = 'https://www.facebook.com/happy.stem.by.carmencita';

    public const DEFAULT_REPLY = 'Thank You! We appreciate you for contacting us, We will reply to you in details once an employee is available or give us a call at +639353505610 or message us on Facebook';

    public function renderedReply(): string
    {
        $reply = e($this->admin_reply ?? '');

        $reply = str_ireplace(
            'message us on facebook',
            'message us on <a href="'.self::FACEBOOK_URL.'" target="_blank" rel="noopener">Facebook</a>',
            $reply
        );

        $reply = str_ireplace(
            '+639353505610',
            '<a href="tel:+639353505610">+639353505610</a>',
            $reply
        );

        return $reply;
    }
}
