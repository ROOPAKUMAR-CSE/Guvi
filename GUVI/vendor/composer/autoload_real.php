<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit80aa1bb66ab44973c6b98031709a4dbc
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit80aa1bb66ab44973c6b98031709a4dbc', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit80aa1bb66ab44973c6b98031709a4dbc', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit80aa1bb66ab44973c6b98031709a4dbc::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
