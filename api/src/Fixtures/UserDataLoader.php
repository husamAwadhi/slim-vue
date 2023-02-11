<?php

namespace App\Fixtures;

use App\Domain\User\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserDataLoader implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = new User(
            username: 'husam',
            firstName: 'Husam',
            lastName: 'Awadhi',
            password: 'password'
        );
        $manager->persist($user);
        $user = new User(
            username: 'frank',
            firstName: 'frank',
            lastName: 'smith',
            password: 'password'
        );
        $manager->persist($user);
        $user = new User(
            username: 'samantha',
            firstName: 'samantha',
            lastName: 'Otwell',
            password: 'password'
        );
        $manager->persist($user);

        $manager->flush();
    }
}
