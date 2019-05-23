<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5b68b99f2e488260f61cc3c4a53f6d21
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5b68b99f2e488260f61cc3c4a53f6d21::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5b68b99f2e488260f61cc3c4a53f6d21::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}