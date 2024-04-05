<?php

unset($CFG);  // Ignore this line
global $CFG;  // This is necessary here for PHPUnit execution
$CFG = new stdClass();

//=========================================================================
// 1. DATABASE SETUP
//=========================================================================
// First, you need to configure the database where all Moodle data       //
// will be stored.  This database must already have been created         //
// and a username/password created to access it.                         //

function loadenv($envName, $default = "") {
    return getenv($envName) ? getenv($envName) : $default;
}

$CFG->dbtype    = loadenv('MOODLE_DB_TYPE', 'mariadb');      // 'pgsql', 'mariadb', 'mysqli', 'sqlsrv' or 'oci'
$CFG->dblibrary = 'native';     // 'native' only at the moment
$CFG->dbhost    = loadenv('MOODLE_DB_HOST', 'localhost');  // eg 'localhost' or 'db.isp.com' or IP
$CFG->dbname    = loadenv('MOODLE_DB_NAME', 'moodle');     // database name, eg moodle
$CFG->dbuser    = loadenv('MOODLE_DB_USER', 'username');   // your database username
$CFG->dbpass    = loadenv('MOODLE_DB_PASSWORD', 'password');   // your database password
$CFG->prefix    = loadenv('MOODLE_DB_PREFIX', 'mdl_');       // prefix to use for all table names
$CFG->dboptions = array(
    'dbpersist' => false,       // should persistent database connections be
                                //  used? set to 'false' for the most stable
                                //  setting, 'true' can improve performance
                                //  sometimes
    'dbsocket'  => false,       // should connection via UNIX socket be used?
                                //  if you set it to 'true' or custom path
                                //  here set dbhost to 'localhost',
                                //  (please note mysql is always using socket
                                //  if dbhost is 'localhost' - if you need
                                //  local port connection use '127.0.0.1')
    'dbport'    => loadenv('MOODLE_DB_PORT', ''),          // the TCP port number to use when connecting
                                //  to the server. keep empty string for the
                                //  default port
    'dbhandlesoptions' => false,// On PostgreSQL poolers like pgbouncer don't
                                // support advanced options on connection.
                                // If you set those in the database then
                                // the advanced settings will not be sent.
    'dbcollation' => 'utf8mb4_unicode_ci', // MySQL has partial and full UTF-8
                                // support. If you wish to use partial UTF-8
                                // (three bytes) then set this option to
                                // 'utf8_unicode_ci', otherwise this option
                                // can be removed for MySQL (by default it will
                                // use 'utf8mb4_unicode_ci'. This option should
                                // be removed for all other databases.
    // 'fetchbuffersize' => 100000, // On PostgreSQL, this option sets a limit
                                // on the number of rows that are fetched into
                                // memory when doing a large recordset query
                                // (e.g. search indexing). Default is 100000.
                                // Uncomment and set to a value to change it,
                                // or zero to turn off the limit. You need to
                                // set to zero if you are using pg_bouncer in
                                // 'transaction' mode (it is fine in 'session'
                                // mode).
);

$CFG->wwwroot   = loadenv('MOODLE_URL', 'http://localhost:8080');
$CFG->dataroot  = '/moodledata';
$CFG->directorypermissions = 02777;
$CFG->admin = 'admin';
if (getenv('REDIS_HOST')) {
    $CFG->session_handler_class = '\core\session\redis';
    $CFG->session_redis_host = loadenv('REDIS_HOST', '127.0.0.1');
    $CFG->session_redis_port = loadenv('REDIS_PORT', 6379);  // Optional.
    $CFG->session_redis_database = loadenv('REDIS_DB', 0);  // Optional, default is db 0.
    $CFG->session_redis_auth = ''; // Optional, default is don't set one.
    $CFG->session_redis_prefix = loadenv('REDIS_PREFIX', ''); // Optional, default is don't set one.
    $CFG->session_redis_acquire_lock_timeout = 120;
    $CFG->session_redis_lock_expire = 7200;
}

$CFG->reverseproxy = filter_var(loadenv('MOODLE_REVERSE_PROXY', false), FILTER_VALIDATE_BOOLEAN);
$CFG->sslproxy = filter_var(loadenv('MOODLE_SSL_PROXY', false), FILTER_VALIDATE_BOOLEAN);
$CFG->localcachedir = '/var/local/cache';      // Intended for local node caching.
$CFG->disableupdateautodeploy = filter_var(loadenv('MOODLE_DISABLE_UPDATE_AUTODEPLOY', false), FILTER_VALIDATE_BOOLEAN);

if (getenv('MOODLE_DEBUG')) {
    @ini_set('display_errors', '1');    // NOT FOR PRODUCTION SERVERS!
    $CFG->debug = (E_ALL | E_STRICT);   // === DEBUG_DEVELOPER - NOT FOR PRODUCTION SERVERS!
    $CFG->debugdisplay = 1;             // NOT FOR PRODUCTION SERVERS!
    $CFG->debugusers = '2';
    $CFG->themedesignermode = true; // NOT FOR PRODUCTION SERVERS!
    $CFG->debugimap = true;
    $CFG->cachejs = false; // NOT FOR PRODUCTION SERVERS!
    $CFG->yuiloginclude = array(
        'moodle-core-dock-loader' => true,
        'moodle-course-categoryexpander' => true,
    );
    $CFG->yuilogexclude = array(
        'moodle-core-dock' => true,
        'moodle-core-notification' => true,
    );

    $CFG->yuiloglevel = 'debug';
    $CFG->langstringcache = false; // NOT FOR PRODUCTION SERVERS!
    $CFG->noemailever = true;    // NOT FOR PRODUCTION SERVERS!
    $CFG->divertallemailsto = 'root@localhost.local'; // NOT FOR PRODUCTION SERVERS!
    $CFG->divertallemailsexcept = 'tester@dev.com, fred(\+.*)?@example.com'; // NOT FOR PRODUCTION SERVERS!
    $CFG->xmldbdisablecommentchecking = true;    // NOT FOR PRODUCTION SERVERS!
    $CFG->upgradeshowsql = true; // NOT FOR PRODUCTION SERVERS!
    $CFG->showcronsql = true;
    $CFG->showcrondebugging = true;
}

$CFG->pathtophp = '/usr/local/bin/php';
$CFG->pathtodu = '/usr/bin/du';
$CFG->aspellpath = '/usr/bin/aspell';
$CFG->pathtodot = '/usr/bin/dot';

require_once(__DIR__ . '/lib/setup.php'); // Do not edit

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!