<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Domain\User\User;
use App\Infrastructure\DatabaseUserRepository;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Tests\TestCase;

final class AuthenticationActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(
            username: 'bill',
            firstName: 'Bill',
            lastName: 'Gates',
            password: 'pop'
        );

        $userRepositoryProphecy = $this->prophesize(DatabaseUserRepository::class);
        $userRepositoryProphecy
            ->getUserOfUsernameAndPassword('bill', 'pop')
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $entityManagerProphecy = $this->prophesize(EntityManager::class);
        $entityManagerProphecy
            ->getRepository(User::class)
            ->willReturn($userRepositoryProphecy->reveal());

        $container->set(EntityManager::class, $entityManagerProphecy->reveal());

        $body = ['username' => 'bill', 'password' => 'pop'];
        $request = $this->createRequest('POST', '/token', body: $body);
        $response = $app->handle($request);
        $payload = json_decode((string) $response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('statusCode', $payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('token', $payload['data']);
    }
}
