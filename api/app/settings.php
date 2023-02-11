<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use App\Authentication\JWT;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder): void {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            $secret = getenv('JWT_SECRET', true);

            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError' => true,
                'logErrorDetails' => true,
                'logger' => [
                    'name' => 'API',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                ],
                JWT::SETTINGS_KEY => $secret,
                JWT::MIDDLEWARE_OPTIONS_KEY => [
                    'path' => ['/balance', '/user'],
                    'secret' => $secret,
                    'algorithm' => JWT::ALGORITHM,
                    'secure' => false, // TODO: proper handling required.
                ],
                'doctrine' => [
                    'dev_mode' => true,
                    'cache_dir' => dirname(__DIR__) . '/var/doctrine',
                    'metadata_dirs' => [dirname(__DIR__) . '/src/Domain'],
                    'connection' => [
                        'url' => getenv('DATABASE_URL', true),
                    ],
                ],
            ]);
        },
    ]);
};
