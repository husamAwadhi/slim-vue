<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path']);
            $logger->pushHandler($handler);

            return $logger;
        },
        ORMSetup::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $doctrineSettings = $settings->get('doctrine');

            $cache = $doctrineSettings['dev_mode'] ?
                new ArrayAdapter() :
                new FilesystemAdapter(directory: $doctrineSettings['cache_dir']);

            return ORMSetup::createAttributeMetadataConfiguration(
                paths: $doctrineSettings['metadata_dirs'],
                isDevMode: $doctrineSettings['dev_mode'],
                cache: $cache
            );
        },
        Connection::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $doctrineSettings = $settings->get('doctrine');

            return DriverManager::getConnection(
                $doctrineSettings['connection'],
                $c->get(ORMSetup::class)
            );
        },
        EntityManager::class => function (ContainerInterface $c) {
            return new EntityManager(
                $c->get(Connection::class),
                $c->get(ORMSetup::class)
            );
        },
    ]);
};
