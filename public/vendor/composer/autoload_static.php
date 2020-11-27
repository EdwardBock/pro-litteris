<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit60014a30e971434c4f80508881fef88d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Palasthotel\\ProLitteris\\' => 24,
        ),
        'H' => 
        array (
            'Html2Text\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Palasthotel\\ProLitteris\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
        'Html2Text\\' => 
        array (
            0 => __DIR__ . '/..' . '/html2text/html2text/src',
            1 => __DIR__ . '/..' . '/html2text/html2text/test',
        ),
    );

    public static $classMap = array (
        'Palasthotel\\ProLitteris\\API' => __DIR__ . '/../..' . '/classes/API.php',
        'Palasthotel\\ProLitteris\\DashboardWidget' => __DIR__ . '/../..' . '/classes/DashboardWidget.php',
        'Palasthotel\\ProLitteris\\Database' => __DIR__ . '/../..' . '/classes/Database.php',
        'Palasthotel\\ProLitteris\\MetaBox' => __DIR__ . '/../..' . '/classes/MetaBox.php',
        'Palasthotel\\ProLitteris\\Model\\FetchPixelsResponse' => __DIR__ . '/../..' . '/classes/Model/FetchPixelsResponse.php',
        'Palasthotel\\ProLitteris\\Model\\Pixel' => __DIR__ . '/../..' . '/classes/Model/Pixel.php',
        'Palasthotel\\ProLitteris\\Model\\_BaseAPIResponse' => __DIR__ . '/../..' . '/classes/Model/_BaseAPIResponse.php',
        'Palasthotel\\ProLitteris\\NoParticipantException' => __DIR__ . '/../..' . '/classes/NoParticipantException.php',
        'Palasthotel\\ProLitteris\\Options' => __DIR__ . '/../..' . '/classes/Options.php',
        'Palasthotel\\ProLitteris\\Post' => __DIR__ . '/../..' . '/classes/Post.php',
        'Palasthotel\\ProLitteris\\PostsTable' => __DIR__ . '/../..' . '/classes/PostsTable.php',
        'Palasthotel\\ProLitteris\\Repository' => __DIR__ . '/../..' . '/classes/Repository.php',
        'Palasthotel\\ProLitteris\\Schedule' => __DIR__ . '/../..' . '/classes/Schedule.php',
        'Palasthotel\\ProLitteris\\Service' => __DIR__ . '/../..' . '/classes/Service.php',
        'Palasthotel\\ProLitteris\\TrackingPixel' => __DIR__ . '/../..' . '/classes/TrackingPixel.php',
        'Palasthotel\\ProLitteris\\User' => __DIR__ . '/../..' . '/classes/User.php',
        'Palasthotel\\ProLitteris\\_Component' => __DIR__ . '/../..' . '/classes/_Component.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit60014a30e971434c4f80508881fef88d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit60014a30e971434c4f80508881fef88d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit60014a30e971434c4f80508881fef88d::$classMap;

        }, null, ClassLoader::class);
    }
}