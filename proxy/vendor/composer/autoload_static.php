<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit496f0b71cd46bf67a293dfeee7a2d501
{
    public static $files = array (
        '3917c79c5052b270641b5a200963dbc2' => __DIR__ . '/..' . '/kint-php/kint/init.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Contracts\\Service\\' => 26,
            'Symfony\\Contracts\\EventDispatcher\\' => 34,
            'Symfony\\Contracts\\Cache\\' => 24,
            'Symfony\\Component\\VarExporter\\' => 30,
            'Symfony\\Component\\EventDispatcher\\' => 34,
            'Symfony\\Component\\Cache\\' => 24,
            'Solarium\\' => 9,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Container\\' => 14,
            'Psr\\Cache\\' => 10,
        ),
        'K' => 
        array (
            'Kint\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Contracts\\Service\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/service-contracts',
        ),
        'Symfony\\Contracts\\EventDispatcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/event-dispatcher-contracts',
        ),
        'Symfony\\Contracts\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/cache-contracts',
        ),
        'Symfony\\Component\\VarExporter\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/var-exporter',
        ),
        'Symfony\\Component\\EventDispatcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/event-dispatcher',
        ),
        'Symfony\\Component\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/cache',
        ),
        'Solarium\\' => 
        array (
            0 => __DIR__ . '/..' . '/solarium/solarium/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'Kint\\' => 
        array (
            0 => __DIR__ . '/..' . '/kint-php/kint/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit496f0b71cd46bf67a293dfeee7a2d501::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit496f0b71cd46bf67a293dfeee7a2d501::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
