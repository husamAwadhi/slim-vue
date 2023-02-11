<?php

namespace Tests\Authentication;

use App\Application\Settings\Settings;
use App\Authentication\JWT;
use App\Domain\User\User;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Psr\Http\Server\MiddlewareInterface;
use Tests\TestCase;
use UnexpectedValueException;

final class JWTTest extends TestCase
{
    public function getJWT(): JWT
    {
        return new JWT(
            new Settings([JWT::SETTINGS_KEY => 'me', JWT::MIDDLEWARE_OPTIONS_KEY => []])
        );
    }

    public function testSuccessfullyGetMiddleware()
    {
        $this->assertInstanceOf(
            MiddlewareInterface::class,
            $this->getJWT()->getMiddlewareInstance()
        );
    }

    /**
     * @dataProvider secretsDataProvider
     */
    public function testSuccessfullyGetSecret(string $secret)
    {
        $jwt = new JWT(
            new Settings([JWT::SETTINGS_KEY => $secret])
        );

        $this->assertEquals($jwt->getSecret(), $secret);
    }
    public function secretsDataProvider()
    {
        return [
            ['some'],
            ['sweet'],
            ['secrets'],
        ];
    }

    /**
     * @dataProvider payloadsDataProvider
     */
    public function testSuccessfullyGenerateDecodableToken(User $user, array $expectedPayload)
    {
        $jwt = $this->getJWT();

        $token = $jwt->generateToken($user, $expectedPayload);

        $actualPayload = $jwt->getPayloadFromToken($token);

        $this->assertEquals($expectedPayload, $actualPayload);
    }
    public function payloadsDataProvider()
    {
        $jwt = $this->getJWT();
        $user1 = new User('husam', 'Husam', 'Awadhi', 'pop1');
        $user2 = new User('ameen', 'Ameen', 'Awadhi', 'pop2');
        $user3 = new User('naseem', 'Naseem', 'Awadhi', 'pop3');

        return [
            [$user1, $jwt->generatePayloadFromUser($user1)],
            [$user2, $jwt->generatePayloadFromUser($user2)],
            [$user3, $jwt->generatePayloadFromUser($user3)],
        ];
    }

    /**
     * @dataProvider invalidPayloadsDataProvider
     */
    public function testSuccessfullyHandlingInvalidToken(User $user, array $payload, string $exception, string $token = null)
    {
        $this->expectException($exception);

        $jwt = $this->getJWT();
        $token = $token ?? $jwt->generateToken($user, $payload);

        $_ = $jwt->getPayloadFromToken($token);
    }
    public function invalidPayloadsDataProvider()
    {
        $user1 = new User('husam', 'Husam', 'Awadhi', 'pop1');
        $user2 = new User('ameen', 'Ameen', 'Awadhi', 'pop2');
        $user3 = new User('naseem', 'Naseem', 'Awadhi', 'pop3');

        return [
            [
                $user1,
                [
                    'iat' => strtotime('-2 day'),
                    'user' => $user1->getUsername(),
                    'exp' => strtotime('-1 day'),
                    'scope' => $user1->getPermissions(),
                ],
                ExpiredException::class
            ],
            [
                $user2,
                [
                    'iat' => strtotime('-2 day'),
                    'user' => $user2->getUsername(),
                    'exp' => strtotime('-1 day'),
                    'scope' => $user2->getPermissions(),
                ],
                UnexpectedValueException::class,
                'hi'
            ],
            [
                $user3,
                [
                    'iat' => strtotime('+1 day'),
                    'user' => $user3->getUsername(),
                    'exp' => strtotime('+2 day'),
                    'scope' => $user3->getPermissions(),
                ],
                BeforeValidException::class
            ],
        ];
    }
}
