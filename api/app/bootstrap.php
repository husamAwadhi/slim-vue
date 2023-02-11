<?php

use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

/**
 * generate \Slim\App instance.
 */
function getApp(): App
{
    // Instantiate PHP-DI ContainerBuilder
    $containerBuilder = new ContainerBuilder();

    // Should be set to enabled in production
    // $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');

    // Set up settings
    $settings = require __DIR__ . '/../app/settings.php';
    $settings($containerBuilder);

    // Set up dependencies
    $dependencies = require __DIR__ . '/../app/dependencies.php';
    $dependencies($containerBuilder);

    // Set up repositories
    $repositories = require __DIR__ . '/../app/repositories.php';
    $repositories($containerBuilder);

    // Build PHP-DI Container instance
    $container = $containerBuilder->build();

    // Instantiate the app
    AppFactory::setContainer($container);

    return AppFactory::create();
}
