<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit52c1682a08c5fae9da1998c301f758bd
{
    public static $files = array (
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
    );

    public static $classMap = array (
        'Jumbojett\\OpenIDConnectClient' => __DIR__ . '/..' . '/jumbojett/openid-connect-php/src/OpenIDConnectClient.php',
        'Jumbojett\\OpenIDConnectClientException' => __DIR__ . '/..' . '/jumbojett/openid-connect-php/src/OpenIDConnectClient.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit52c1682a08c5fae9da1998c301f758bd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit52c1682a08c5fae9da1998c301f758bd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit52c1682a08c5fae9da1998c301f758bd::$classMap;

        }, null, ClassLoader::class);
    }
}
