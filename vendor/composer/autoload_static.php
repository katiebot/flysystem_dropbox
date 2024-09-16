<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita909320cb4d833cfbb8374be9c55908b
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stevenmaguire\\OAuth2\\Client\\' => 28,
            'Spatie\\FlysystemDropbox\\' => 24,
            'Spatie\\Dropbox\\' => 15,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
        ),
        'L' => 
        array (
            'League\\OAuth2\\Client\\' => 21,
            'League\\MimeTypeDetection\\' => 25,
            'League\\Flysystem\\Local\\' => 23,
            'League\\Flysystem\\' => 17,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
            'GrahamCampbell\\GuzzleFactory\\' => 29,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stevenmaguire\\OAuth2\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/stevenmaguire/oauth2-dropbox/src',
        ),
        'Spatie\\FlysystemDropbox\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/flysystem-dropbox/src',
        ),
        'Spatie\\Dropbox\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/dropbox-api/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
            1 => __DIR__ . '/..' . '/psr/http-factory/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'League\\OAuth2\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/oauth2-client/src',
        ),
        'League\\MimeTypeDetection\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/mime-type-detection/src',
        ),
        'League\\Flysystem\\Local\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/flysystem-local',
        ),
        'League\\Flysystem\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/flysystem/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'GrahamCampbell\\GuzzleFactory\\' => 
        array (
            0 => __DIR__ . '/..' . '/graham-campbell/guzzle-factory/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita909320cb4d833cfbb8374be9c55908b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita909320cb4d833cfbb8374be9c55908b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita909320cb4d833cfbb8374be9c55908b::$classMap;

        }, null, ClassLoader::class);
    }
}
