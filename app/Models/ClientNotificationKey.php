<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientNotificationKey extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (ClientNotificationKey $notificationKey) {
            $notificationKey->notification_api_key = $notificationKey->generateLicenseKey($notificationKey->id, 'o4u-firebase-notification-key');
            $notificationKey->save();
        });
    }

    protected $fillable = [
        'name', 'notification_api_key',
    ];

    /**
     * Generate a license key.
     *
     * @param string $userId
     * @param string $productId
     * @param string $secretKey
     * @return string
     */
    public function generateLicenseKey(string $userId, string $secretKey): string
    {
        // Combine inputs
        $rawData = $userId . ':' . $secretKey;

        // Hash the raw data
        $hash = hash('sha256', $rawData);

        // Encode the hash in Base64
        $encoded = base64_encode($hash);

        // Format the key (split into 5-character groups)
        $formattedKey = strtoupper(implode('-', str_split($encoded, 5)));

        return $formattedKey;
    }

    /**
     * Verify a license key.
     *
     * @param string $userId
     * @param string $productId
     * @param string $secretKey
     * @param string $licenseKey
     * @return bool
     */
    public function verifyLicenseKey(string $userId, string $productId, string $secretKey, string $licenseKey): bool
    {
        $expectedKey = $this->generateLicenseKey($userId, $productId, $secretKey);

        // Normalize for comparison
        return str_replace('-', '', $expectedKey) === str_replace('-', '', strtoupper($licenseKey));
    }
}
