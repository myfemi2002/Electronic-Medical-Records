<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'default_message',
        'is_active'
    ];

    /**
     * Generate a WhatsApp API URL with the given message
     * 
     * @param string $message Custom message to send
     * @return string WhatsApp API URL
     */
    public function generateWhatsAppUrl($message = null)
    {
        $phone = $this->phone_number;
        $text = urlencode($message ?? $this->default_message);
        
        return "https://wa.me/{$phone}?text={$text}";
    }

    /**
     * Get the active WhatsApp setting
     * 
     * @return WhatsAppSetting|null
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
