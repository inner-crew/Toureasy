<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;

/**
 * This class is copied in every Composer installed project and available to all
 *
 * To require it's presence, you can require `composer-runtime-api ^2.0`
 */
class InstalledVersions
{
    private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => 'cf0f218631363b37e81d20ff80861da0b915a99d',
    'name' => '__root__',
  ),
  'versions' => 
  array (
    '__root__' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => 'cf0f218631363b37e81d20ff80861da0b915a99d',
    ),
    'container-interop/container-interop' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '79cbf1341c22ec75643d841642dd5d6acd83bdb8',
    ),
    'doctrine/inflector' => 
    array (
      'pretty_version' => '2.0.3',
      'version' => '2.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '9cf661f4eb38f7c881cac67c75ea9b00bf97b210',
    ),
    'illuminate/collections' => 
    array (
      'pretty_version' => 'v8.58.0',
      'version' => '8.58.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '673d71d9db2827b04c096c4fe9739edddea14ac4',
    ),
    'illuminate/container' => 
    array (
      'pretty_version' => 'v8.58.0',
      'version' => '8.58.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ecb645e1838cdee62eebd07ded490b1de5505f35',
    ),
    'illuminate/contracts' => 
    array (
      'pretty_version' => 'v8.58.0',
      'version' => '8.58.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '7354badf7b57eae805a56d1163fb023e0f58a639',
    ),
    'illuminate/database' => 
    array (
      'pretty_version' => 'v8.58.0',
      'version' => '8.58.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '146c58493e02bce86ff19445d4435421fd895337',
    ),
    'illuminate/macroable' => 
    array (
      'pretty_version' => 'v8.58.0',
      'version' => '8.58.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '300aa13c086f25116b5f3cde3ca54ff5c822fb05',
    ),
    'illuminate/support' => 
    array (
      'pretty_version' => 'v8.58.0',
      'version' => '8.58.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5e8f059a5d1f298324e847822b997778a3472128',
    ),
    'nesbot/carbon' => 
    array (
      'pretty_version' => '2.52.0',
      'version' => '2.52.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '369c0e2737c56a0f39c946dd261855255a6fccbe',
    ),
    'nikic/fast-route' => 
    array (
      'pretty_version' => 'v1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '181d480e08d9476e61381e04a71b34dc0432e812',
    ),
    'pimple/pimple' => 
    array (
      'pretty_version' => 'v3.4.0',
      'version' => '3.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '86406047271859ffc13424a048541f4531f53601',
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.1.1',
      'version' => '1.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '8622567409010282b7aeebe4bb841fe98b58dcaf',
    ),
    'psr/container-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/http-message' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6561bf28d520154e4b0ec72be95418abe6d9363',
    ),
    'psr/http-message-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0|2.0',
      ),
    ),
    'psr/simple-cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '408d5eafb83c57f6365a3ca330ff23aa4a5fa39b',
    ),
    'slim/slim' => 
    array (
      'pretty_version' => '3.12.1',
      'version' => '3.12.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'eaee12ef8d0750db62b8c548016d82fb33addb6b',
    ),
    'symfony/console' => 
    array (
      'pretty_version' => 'v5.3.7',
      'version' => '5.3.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '8b1008344647462ae6ec57559da166c2bfa5e16a',
    ),
    'symfony/deprecation-contracts' => 
    array (
      'pretty_version' => 'v2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5f38c8804a9e97d23e0c8d63341088cd8a22d627',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'v1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '46cd95797e9df938fdd2b03693b5fca5e64b01ce',
    ),
    'symfony/polyfill-intl-grapheme' => 
    array (
      'pretty_version' => 'v1.23.1',
      'version' => '1.23.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '16880ba9c5ebe3642d1995ab866db29270b36535',
    ),
    'symfony/polyfill-intl-normalizer' => 
    array (
      'pretty_version' => 'v1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8590a5f561694770bdcd3f9b5c69dde6945028e8',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.23.1',
      'version' => '1.23.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '9174a3d80210dca8daa7f31fec659150bbeabfc6',
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'v1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fba8933c384d6476ab14fb7b8526e5287ca7e010',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.23.1',
      'version' => '1.23.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '1100343ed1a92e3a38f9ae122fc0eb21602547be',
    ),
    'symfony/service-contracts' => 
    array (
      'pretty_version' => 'v2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f040a30e04b57fbcc9c6cbcf4dbaa96bd318b9bb',
    ),
    'symfony/string' => 
    array (
      'pretty_version' => 'v5.3.7',
      'version' => '5.3.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '8d224396e28d30f81969f083a58763b8b9ceb0a5',
    ),
    'symfony/translation' => 
    array (
      'pretty_version' => 'v5.3.7',
      'version' => '5.3.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '4d595a6d15fd3a2c67f6f31d14d15d3b7356d7a6',
    ),
    'symfony/translation-contracts' => 
    array (
      'pretty_version' => 'v2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '95c812666f3e91db75385749fe219c5e494c7f95',
    ),
    'symfony/translation-implementation' => 
    array (
      'provided' => 
      array (
        0 => '2.3',
      ),
    ),
    'voku/portable-ascii' => 
    array (
      'pretty_version' => '1.5.6',
      'version' => '1.5.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '80953678b19901e5165c56752d087fc11526017c',
    ),
  ),
);
    private static $canGetVendors;
    private static $installedByVendor = array();

    /**
     * Returns a list of all package names which are present, either by being installed, replaced or provided
     *
     * @return string[]
     * @psalm-return list<string>
     */
    public static function getInstalledPackages()
    {
        $packages = array();
        foreach (self::getInstalled() as $installed) {
            $packages[] = array_keys($installed['versions']);
        }


        if (1 === \count($packages)) {
            return $packages[0];
        }

        return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
    }

    /**
     * Checks whether the given package is installed
     *
     * This also returns true if the package name is provided or replaced by another package
     *
     * @param  string $packageName
     * @return bool
     */
    public static function isInstalled($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (isset($installed['versions'][$packageName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the given package satisfies a version constraint
     *
     * e.g. If you want to know whether version 2.3+ of package foo/bar is installed, you would call:
     *
     *   Composer\InstalledVersions::satisfies(new VersionParser, 'foo/bar', '^2.3')
     *
     * @param VersionParser $parser      Install composer/semver to have access to this class and functionality
     * @param string        $packageName
     * @param string|null   $constraint  A version constraint to check for, if you pass one you have to make sure composer/semver is required by your package
     *
     * @return bool
     */
    public static function satisfies(VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));

        return $provided->matches($constraint);
    }

    /**
     * Returns a version constraint representing all the range(s) which are installed for a given package
     *
     * It is easier to use this via isInstalled() with the $constraint argument if you need to check
     * whether a given version of a package is installed, and not just whether it exists
     *
     * @param  string $packageName
     * @return string Version constraint usable with composer/semver
     */
    public static function getVersionRanges($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            $ranges = array();
            if (isset($installed['versions'][$packageName]['pretty_version'])) {
                $ranges[] = $installed['versions'][$packageName]['pretty_version'];
            }
            if (array_key_exists('aliases', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
            }
            if (array_key_exists('replaced', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
            }
            if (array_key_exists('provided', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
            }

            return implode(' || ', $ranges);
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getPrettyVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['pretty_version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['pretty_version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as reference
     */
    public static function getReference($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['reference'])) {
                return null;
            }

            return $installed['versions'][$packageName]['reference'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @return array
     * @psalm-return array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}
     */
    public static function getRootPackage()
    {
        $installed = self::getInstalled();

        return $installed[0]['root'];
    }

    /**
     * Returns the raw installed.php data for custom implementations
     *
     * @return array[]
     * @psalm-return array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>}
     */
    public static function getRawData()
    {
        return self::$installed;
    }

    /**
     * Lets you reload the static array from another file
     *
     * This is only useful for complex integrations in which a project needs to use
     * this class but then also needs to execute another project's autoloader in process,
     * and wants to ensure both projects have access to their version of installed.php.
     *
     * A typical case would be PHPUnit, where it would need to make sure it reads all
     * the data it needs from this class, then call reload() with
     * `require $CWD/vendor/composer/installed.php` (or similar) as input to make sure
     * the project in which it runs can then also use this class safely, without
     * interference between PHPUnit's dependencies and the project's dependencies.
     *
     * @param  array[] $data A vendor/composer/installed.php data set
     * @return void
     *
     * @psalm-param array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>} $data
     */
    public static function reload($data)
    {
        self::$installed = $data;
        self::$installedByVendor = array();
    }

    /**
     * @return array[]
     */
    private static function getInstalled()
    {
        if (null === self::$canGetVendors) {
            self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
        }

        $installed = array();

        if (self::$canGetVendors) {
            // @phpstan-ignore-next-line
            foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
                if (isset(self::$installedByVendor[$vendorDir])) {
                    $installed[] = self::$installedByVendor[$vendorDir];
                } elseif (is_file($vendorDir.'/composer/installed.php')) {
                    $installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
                }
            }
        }

        $installed[] = self::$installed;

        return $installed;
    }
}
