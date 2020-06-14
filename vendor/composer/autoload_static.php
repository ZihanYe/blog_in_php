<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4d25e616a3f3dcf97cdb520c812a9442
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4d25e616a3f3dcf97cdb520c812a9442::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4d25e616a3f3dcf97cdb520c812a9442::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
