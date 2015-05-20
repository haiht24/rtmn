<?php
/**
 * Set the umask
 */
umask(0000);

/**
 * Load composer
 */
require_once APP . '/Vendor/autoload.php';

/**
 * Load scout2go config
 */
Configure::load('mcus');

/**
 * CakePHP Debug Level:
 *
 * Production Mode:
 *     0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 *     1: Errors and warnings shown, model caches refreshed, flash messages halted.
 *     2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */
//Configure::write('debug', 2); done in scout2go.php

/**
 * Configure the Error handler used to handle errors for your application.  By default
 * ErrorHandler::handleError() is used.  It will display errors using Debugger, when debug > 0
 * and log errors with CakeLog when debug = 0.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle errors. You can set this to any callback type,
 *    including anonymous functions.
 * - `level` - int - The level of errors you are interested in capturing.
 * - `trace` - boolean - Include stack traces for errors in log files.
 *
 * @see ErrorHandler for more information on error handling and configuration.
 */
Configure::write('Error', array(
    'handler' => 'ErrorHandler::handleError',
    'level' => E_ALL & ~E_DEPRECATED & ~E_STRICT,
    'trace' => true
));

/**
 * Configure the Exception handler used for uncaught exceptions.  By default,
 * ErrorHandler::handleException() is used. It will display a HTML page for the exception, and
 * while debug > 0, framework errors like Missing Controller will be displayed.  When debug = 0,
 * framework errors will be coerced into generic HTTP errors.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle exceptions. You can set this to any callback type,
 *   including anonymous functions.
 * - `renderer` - string - The class responsible for rendering uncaught exceptions.  If you choose a custom class you
 *   should place the file for that class in app/Lib/Error. This class needs to implement a render method.
 * - `log` - boolean - Should Exceptions be logged?
 *
 * @see ErrorHandler for more information on exception handling and configuration.
 */
Configure::write('Exception', array(
    'handler' => 'ErrorHandler::handleException',
    'renderer' => 'mCusFrontendExceptionRenderer',
    'log' => true
));

/**
 * Application wide charset encoding
 */
Configure::write('App.encoding', 'UTF-8');

/**
 * To configure CakePHP *not* to use mod_rewrite and to
 * use CakePHP pretty URLs, remove these .htaccess
 * files:
 *
 * /.htaccess
 * /app/.htaccess
 * /app/webroot/.htaccess
 *
 * And uncomment the App.baseUrl below. But keep in mind
 * that plugin assets such as images, CSS and JavaScript files
 * will not work without URL rewriting!
 * To work around this issue you should either symlink or copy
 * the plugin assets into you app's webroot directory. This is
 * recommended even when you are using mod_rewrite. Handling static
 * assets through the Dispatcher is incredibly inefficient and
 * included primarily as a development convenience - and
 * thus not recommended for production applications.
 */
//Configure::write('App.baseUrl', env('SCRIPT_NAME'));

/**
 * To configure CakePHP to use a particular domain URL
 * for any URL generation inside the application, set the following
 * configuration variable to the http(s) address to your domain. This
 * will override the automatic detection of full base URL and can be
 * useful when generating links from the CLI (e.g. sending emails)
 */
//Configure::write('App.fullBaseUrl', 'http://example.com');

/**
 * Web path to the public images directory under webroot.
 * If not set defaults to 'img/'
 */
//Configure::write('App.imageBaseUrl', 'img/');

/**
 * Web path to the CSS files directory under webroot.
 * If not set defaults to 'css/'
 */
//Configure::write('App.cssBaseUrl', 'css/');

/**
 * Web path to the js files directory under webroot.
 * If not set defaults to 'js/'
 */
//Configure::write('App.jsBaseUrl', 'js/');

/**
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 *    Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 *    `admin_index()` and `/admin/controller/index`
 *    `manager_index()` and `/manager/controller/index`
 *
 */
//Configure::write('Routing.prefixes', array('admin'));

/**
 * Turn off all caching application-wide.
 *
 */
//Configure::write('Cache.disable', true);

/**
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * public $cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting public $cacheAction = true,
 * or in each action using $this->cacheAction = true.
 *
 */
//Configure::write('Cache.check', true);

/**
 * Enable cache view prefixes.
 *
 * If set it will be prepended to the cache name for view file caching. This is
 * helpful if you deploy the same application via multiple subdomains and languages,
 * for instance. Each version can then have its own view cache namespace.
 * Note: The final cache file name will then be `prefix_cachefilename`.
 */
//Configure::write('Cache.viewPrefix', 'prefix');

Configure::write('Session', array(
    'cookie' => 'mcus_session',
    'timeout' => 10000,
    'checkAgent' => false,
    'handler' => array(
        'engine' => 'CacheSession',
        'config' => '_sessions_'
    ),
    //http://php.net/manual/en/session.configuration.php
    'ini' => array(
        'session.cookie_secure' => false,
        'session.use_trans_sid' => 0,
        'url_rewriter.tags' => '',
        //'session.auto_start' => 0,
        'session.use_cookies' => 1,
        'session.cookie_path' => '/',
        'session.save_handler' => 'user',
    )
));

/**
 * A random string used in security hashing methods.
 */
Configure::write('Security.salt', 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mii');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
Configure::write('Security.cipherSeed', '768593096574535424967496836455');

/**
 * Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a querystring parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps when debug > 0. Set to 'force' to always enable
 * timestamping regardless of debug value.
 */
Configure::write('Asset.timestamp', 'force');

/**
 * The class name and database used in CakePHP's
 * access control lists.
 */
Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');

/**
 * Uncomment this line and correct your server timezone to fix
 * any date & time related errors.
 */
date_default_timezone_set('America/Los_Angeles');

/**
 * `Config.timezone` is available in which you can set users' timezone string.
 * If a method of CakeTime class is called with $timezone parameter as null and `Config.timezone` is set,
 * then the value of `Config.timezone` will be used. This feature allows you to set users' timezone just
 * once instead of passing it each time in function calls.
 */
Configure::write('Config.timezone', 'America/New_York');

/**
 *
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 *     Cache::config('default', array(
 *        'engine' => 'File', //[required]
 *        'duration' => 3600, //[optional]
 *        'probability' => 100, //[optional]
 *        'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 *        'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 *        'lock' => false, //[optional]  use file locking
 *        'serialize' => true, //[optional]
 *        'mask' => 0664, //[optional]
 *    ));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 *     Cache::config('default', array(
 *        'engine' => 'Apc', //[required]
 *        'duration' => 3600, //[optional]
 *        'probability' => 100, //[optional]
 *        'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *    ));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 *     Cache::config('default', array(
 *        'engine' => 'Xcache', //[required]
 *        'duration' => 3600, //[optional]
 *        'probability' => 100, //[optional]
 *        'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *        'user' => 'user', //user from xcache.admin.user settings
 *        'password' => 'password', //plaintext password (xcache.admin.pass)
 *    ));
 *
 * Memcache (http://www.danga.com/memcached/)
 *
 *     Cache::config('default', array(
 *        'engine' => 'Memcache', //[required]
 *        'duration' => 3600, //[optional]
 *        'probability' => 100, //[optional]
 *        'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *        'servers' => array(
 *            '127.0.0.1:11211' // localhost, default port 11211
 *        ), //[optional]
 *        'persistent' => true, // [optional] set this to false for non-persistent connections
 *        'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 *    ));
 *
 *  Wincache (http://php.net/wincache)
 *
 *     Cache::config('default', array(
 *        'engine' => 'Wincache', //[required]
 *        'duration' => 3600, //[optional]
 *        'probability' => 100, //[optional]
 *        'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *    ));
 */

/**
 * Configure the cache handlers that CakePHP will use for internal
 * metadata like class maps, and model schema.
 *
 * By default File is used, but for improved performance you should use APC.
 *
 * Note: 'default' and other application caches should be configured in app/Config/bootstrap.php.
 *       Please check the comments in bootstrap.php for more info on the cache engines available
 *       and their settings.
 */
$engine = 'File';
$delimiter = '_';
$duration = '+29 days';
if (Configure::read('debug') >= 1) {
    $duration = '+1 seconds';
}


$prefix = 'frontend' . $delimiter;

Cache::config('default', array(
    'engine' => 'Memcache', //[required]
    'duration' => 3600, //[optional]
    'probability' => 100, //[optional]
    'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
    'servers' => array(
        '127.0.0.1:11211' // localhost, default port 11211
    ), //[optional]
    'persistent' => true, // [optional] set this to false for non-persistent connections
    'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
));

Cache::config('default', array(
    'engine' => $engine,
    'duration' => $duration,
    'prefix' => $prefix,
));

Cache::config('_sessions_', array(
    'engine' => $engine,
    'path' => TMP . 'sessions' . DS,
    'duration' => '+' . Configure::read('Session.timeout') . ' minutes',
    'prefix' => $prefix . 'session' . $delimiter,
    'serialize' => ($engine === 'File')
));

/**
 * Configure the cache used for general framework caching.  Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
    'engine' => $engine,
    'prefix' => $prefix . 'cake_core' . $delimiter,
    'path' => CACHE . 'persistent' . DS,
    'serialize' => ($engine === 'File'),
    'duration' => $duration
));

/**
 * Configure the cache used by the less controller
 */
Cache::config('_less_', array(
    'engine' => $engine,
    'prefix' => $prefix . 'less' . $delimiter,
    'path' => CACHE . 'less' . DS,
    'serialize' => ($engine === 'File'),
    'duration' => $duration
));

/**
 * Configure the cache used by the seo controller
 */
Cache::config('_seo_', array(
    'engine' => $engine,
    'prefix' => $prefix . 'seo' . $delimiter,
    'path' => CACHE . 'seo' . DS,
    'serialize' => ($engine === 'File'),
    'duration' => '1 day'
));

Configure::write('AllowedExtensions', array('jpg', 'jpeg', 'png', 'gif', 'bmp'));
Configure::write('facebookPassword', 'e5Br63Udfe');
Configure::write('UnallowedStores', ['home', 'pages', 'proxy', 'upload', 'less', 'proxy', 'seo', 'users', 'stores',
    'coupons', 'activation', 'landing', 'deals', 'categories', 'admin', 'AboutUs', 'DownloadApp', 'PressCentre',
    'CareerPage', 'HelpPage', 'HowTo', 'ContactUs', 'AboutCookies', 'TermsPage', 'PrivacyPolicy', 'AppTerms', 'CompetitionTerms', 'DirectAdv']);
Configure::write('reCaptcha', ['public_key' => '6Lfr5QATAAAAAOFjNFPOk5lQwhCQsIUZ2Ez23OvL', 'secret_key' => '6Lfr5QATAAAAABKo7b5rc4uteDEQzO_rtAniFLG8']);

