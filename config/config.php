<?php
	
	/**
	 * @ author prevdev@gmail.com
	 * @ 2013.05
	 */
	
	if (!defined('PMC')) {
		exit();
	}
	
	session_start();
	
	require ROOT_DIR . '/lib/functions/common.function.php';
	
	
	/**
	 * Define PMC version
	 */
	define('PMC_VERSION', '0.1.2');
	
	
	/**
	 * Define text encoding
	 * Default : utf-8
	 */
	define('TEXT_ENCODING', 'utf-8');
	
	
	/**
	 * Define X_UA_Compatible (ie edge)
	 */
	define('X_UA_Compatible', 'IE=Edge,chrome=1');
	
	
	/**
	 * Define default Layout name
	 */
	define('LAYOUT_NAME', 'default');
	
	
	/**
	 * Define using short url
	 * ex) menu -> http://engine-pmc.org/freeboard
	 * ex) article -> http://engine-pmc.org/15
	 */
	define('USE_SHORT_URL', true);
	
	
	/**
	  * Define default locale
	  * Get current locale by calling getLocale() func
	  */
	define('DEFAULT_LOCALE', 'kr');
	
	
	/**
	 * define using ob_gzhandler
	 */
	define('OB_GZIP', true);
	
	
	/**
	 * define zipping blank
	 */
	define('ZIP_BLANK', true);
	
	
		/**
		 * define line end str
		 * if zipping blank, \r\n str / else ''
		 */
		define('LINE_END', (ZIP_BLANK ? '' : "\r\n"));
	
	
	/**
	 * Define Debugging mode
	 * if do not define this, it will be defined automatically
	 */
	//define('DEBUG_MODE', true);
	
	
	/**
	 * Define log file's path
	 */
	define('LOG_FILE_PATH', (ROOT_DIR . '/pmc_error.log'));
	
	
	/**
	 * Define server info file's path
	 * Default path is '/config/server-info.txt'
	 * It will be used to get server info
	 */
	define('SERVER_INFO_FILE_PATH', (ROOT_DIR . '/config/server-info.json'));
	
	
		/**
		 * Define realavite url (=root url)
		 */
		define('RELATIVE_URL', getRelativeUrl());
		
		
		/**
		 * Define realavite url (=root url)
		 */
		define('SESSION_DOMAIN', getSessionDomain());
	
	
		/**
		 * Define now request's protocol
		 */
		define('PROTOCOL', $_SERVER['HTTPS'] ? 'https':'http');
	
	
		/**
		 * Define real url
		 */
		define('REAL_URI', PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		define('REAL_URL', REAL_URI);
	
	
	/**
	 * login file url
	 */
	//define('LOGIN_URL', RELATIVE_URL . '/?module=login&action=dispLoginPage');
	define('LOGIN_URL', RELATIVE_URL . '/login');
	
	/**
	 * define sso process file url
	 */
	define('SSO_URL', RELATIVE_URL . '/pmc.sso.php');
	
	
	/**
	 * Define Document Type
	 * html5, xhtml-t, xhtml-s, xhtml, html4-t, html4, none
	 */
	define('DOCTYPE', 'html5');
	
	
	header('Content-Type: text/html; charset=' . TEXT_ENCODING);
	ini_set('display_errors', (DEBUG_MODE ? 1 : 0));
	
	
	require ROOT_DIR . '/config/database.php';
	
	require ROOT_DIR . '/lib/classes/Handler.class.php';
	require ROOT_DIR . '/lib/classes/DBHandler.class.php';
	require ROOT_DIR . '/lib/classes/ModuleHandler.class.php';
	require ROOT_DIR . '/lib/classes/ModuleBase.class.php';
	require ROOT_DIR . '/lib/classes/HeaderTagHandler.class.php';
	require ROOT_DIR . '/lib/classes/ErrorLogger.class.php';
	require ROOT_DIR . '/lib/classes/CacheHandler.class.php';
	require ROOT_DIR . '/lib/classes/Context.class.php';
	require ROOT_DIR . '/lib/classes/SSOHandler.class.php';
	
	
	
	
	
	