<?php

declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;

final class UserTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            ['bill.gates', 'Bill', 'Gates', '8z1T!vN65C'],
            ['steve.jobs', 'steve', 'Jobs', 's6ld5#7J5g'],
            ['mark.zuckerberg', 'Mark', 'Zuckerberg', '0sU1&64SLG'],
            ['evan.spiegel', 'Evan', 'spiegel', 'EOy9*zS$56'],
            ['jack.dorsey', 'Jack', 'Dorsey', 'i%Kh426*u3'],
        ];
    }

    /**
     * @dataProvider userProvider
     */
    public function testGetters(string $username, string $firstName, string $lastName, string $password)
    {
        $user = new User($username, $firstName, $lastName, $password);

        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals(ucfirst($firstName), $user->getFirstName());
        $this->assertEquals(ucfirst($lastName), $user->getLastName());
    }

    /**
     * @dataProvider passwordsDataProvider
     */
    public function testAuthenticateUserByPassword(string $password, string $hashedPassword)
    {
        $this->assertTrue((new User('', '', '', ''))->authenticate($password, $hashedPassword));
    }
    public function passwordsDataProvider(): array
    {
        return [
            ['8z1T!vN65C', '$2y$11$xGewbPYIIm10Av2H3XJI1eal3.we0k51CyegW3Lkouf5fXDNCQcB2'],
            ['s6ld5#7J5g', '$2y$11$WXAvArl/pLIrJshweFaEwetB/lAx0HZyFlvi7IFlcTUGKJHrh3XO2'],
            ['0sU1&64SLG', '$2y$11$NSDNc7mqWUHzw.C.QS6H0Onf4qNHVtWicbNDUr13ZsIyx.1W1NAGO'],
            ['EOy9*zS$56', '$2y$11$tbk2B2mMlyar4iXQ/AAi4OvSPPZ1k9TVhbS.ykTV0VcbGIADtRNJm'],
            ['i%Kh426*u3', '$2y$11$Qn4QNuWqlDjVYYiSK8yqm.RCHnJb35SoIlMC0jCZraOiwoZX1vKui'],
        ];
    }
    /**
     * @dataProvider userProvider
     */
    public function testJsonSerialize(string $username, string $firstName, string $lastName, string $password)
    {
        $user = new User($username, $firstName, $lastName, $password);

        $expectedPayload = json_encode([
            'username' => $username,
            'firstName' => ucfirst($firstName),
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }
}
