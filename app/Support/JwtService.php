<?php

namespace App\Support;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;

class JwtService
{
    public function issueToken(User $user): string
    {
        $now = CarbonImmutable::now();
        $ttlMinutes = (int) config('jwt.ttl_minutes', 60);

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'iss' => config('app.url'),
            'aud' => config('app.name', 'NumNam Mobile API'),
            'iat' => $now->timestamp,
            'nbf' => $now->subSeconds(5)->timestamp,
            'exp' => $now->addMinutes($ttlMinutes)->timestamp,
            'sub' => (string) $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ];

        $encodedHeader = $this->base64UrlEncode((string) json_encode($header));
        $encodedPayload = $this->base64UrlEncode((string) json_encode($payload));

        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secret(), true);
        $encodedSignature = $this->base64UrlEncode($signature);

        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

    public function parseToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secret(), true)
        );

        if (! hash_equals($expectedSignature, $encodedSignature)) {
            return null;
        }

        $payloadJson = $this->base64UrlDecode($encodedPayload);
        if ($payloadJson === null) {
            return null;
        }

        $payload = json_decode($payloadJson, true);
        if (! is_array($payload)) {
            return null;
        }

        $now = CarbonImmutable::now()->timestamp;
        $nbf = isset($payload['nbf']) ? (int) $payload['nbf'] : null;
        $exp = isset($payload['exp']) ? (int) $payload['exp'] : null;

        if ($nbf !== null && $now < $nbf) {
            return null;
        }

        if ($exp !== null && $now >= $exp) {
            return null;
        }

        return $payload;
    }

    private function secret(): string
    {
        $secret = (string) config('jwt.secret', '');
        if ($secret !== '') {
            return $secret;
        }

        $appKey = (string) config('app.key', '');

        if (str_starts_with($appKey, 'base64:')) {
            $decoded = base64_decode(substr($appKey, 7), true);
            if ($decoded !== false) {
                return $decoded;
            }
        }

        if ($appKey !== '') {
            return $appKey;
        }

        Log::warning('JWT secret is not configured. Falling back to an unsafe default secret.');

        return 'numnam-api-unsafe-default-secret';
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): ?string
    {
        $padded = strtr($value, '-_', '+/');
        $mod = strlen($padded) % 4;

        if ($mod > 0) {
            $padded .= str_repeat('=', 4 - $mod);
        }

        $decoded = base64_decode($padded, true);

        return $decoded === false ? null : $decoded;
    }
}