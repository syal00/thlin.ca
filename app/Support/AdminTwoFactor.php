<?php

namespace App\Support;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FA\Google2FA;

class AdminTwoFactor
{
    /** Accept codes from adjacent 30-second windows (about ±2 minutes total). */
    private const TOTP_WINDOW = 4;

    public static function normalizeCode(?string $code): string
    {
        return preg_replace('/\D+/', '', (string) $code);
    }

    public static function generateSecret(): string
    {
        return (new Google2FA)->generateSecretKey();
    }

    public static function verify(string $secret, string $code): bool
    {
        $normalized = self::normalizeCode($code);

        if (strlen($normalized) !== 6) {
            return false;
        }

        return (new Google2FA)->verifyKey($secret, $normalized, self::TOTP_WINDOW);
    }

    public static function qrCodeSvg(User $user, string $secret): string
    {
        $url = (new Google2FA)->getQRCodeUrl(
            config('admin.two_factor_issuer', 'THLIN CMS'),
            $user->email,
            $secret
        );

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd
            )
        );

        return $writer->writeString($url);
    }

    public static function confirmSetup(User $user, string $secret, string $code): bool
    {
        if (! self::verify($secret, $code)) {
            return false;
        }

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        return true;
    }
}
