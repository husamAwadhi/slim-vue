<?php

namespace App\Authentication;

use App\Application\Settings\SettingsInterface;
use App\Domain\User\User;
use Firebase\JWT\JWT as FirebaseJWT;
use Psr\Http\Server\MiddlewareInterface;
use Tuupola\Middleware\JwtAuthentication;
use UnexpectedValueException;

class JWT
{
    public const TOKEN_EXP = '+1 day';
    public const ALGORITHM = 'HS256';
    public const SETTINGS_KEY = 'JWTSecret';
    public const MIDDLEWARE_OPTIONS_KEY = 'JWTMiddleware';

    public function __construct(
        protected SettingsInterface $settings
    ) {
    }

    public function getSecret(): string
    {
        return $this->settings->get(self::SETTINGS_KEY);
    }

    public function generatePayloadFromUser(User $user): array
    {
        return [
            'iat' => time(),
            'user' => $user->getUsername(),
            'exp' => strtotime(self::TOKEN_EXP),
            'scope' => $user->getPermissions(),
        ];
    }

    public function generateToken(User $user, ?array $payload = null): string
    {
        $payload = $payload ?? $this->generatePayloadFromUser($user);

        return FirebaseJWT::encode($payload, $this->getSecret(), self::ALGORITHM);
    }

    public function getPayloadFromToken(string $token): array
    {
        if (
            strlen($token) < 1 ||
            count(explode('.', $token)) !== 3
        ) {
            throw new UnexpectedValueException('Invalid token format');
        }

        $token = preg_split('#\s+#', $token, 2)[1] ?? $token;
        $decodedToken = FirebaseJWT::decode($token, $this->getSecret(), [self::ALGORITHM]);

        return json_decode(json_encode($decodedToken), true) ?? [];
    }

    public function getMiddlewareInstance(): MiddlewareInterface
    {
        return new JwtAuthentication($this->settings->get(self::MIDDLEWARE_OPTIONS_KEY));
    }
}
