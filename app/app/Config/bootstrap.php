<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file 
 * after core.php
 *
 * This file should load/create any application wide configuration 
 * settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide 
 * global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); // Loads a single plugin named DebugKit
 */
CakePlugin::load('DebugKit');
CakePlugin::load('AclExtras');


/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));


/***
 * EJQ CONSTANTS
 */
define ('EJQ_MARKETPLACE_SLUG', 'easy-job-quote');
define ('EJQ_META_PROVIDER_ESTIMATION_SLUG', 'estimator');
define ('EJQ_META_PROVIDER_CONTRACTOR_SLUG', 'contractor');
define ('EJQ_META_CONSUMER_HOME_OWNER_SLUG', 'home-owner');
define ('EJQ_GENERAL_CONTRACTOR_SLUG', 'general-contractor');

define ('EJQ_VISIT_PRICE', '149.00');
define ('EJQ_CONTRACTOR_COMMISSION', '0.09');
define ('EJQ_GST_TAX', '0.05');


define ('EJQ_DEMAND_STATUS_REQUEST_OPEN', 'REQUEST_OPEN');
define ('EJQ_DEMAND_STATUS_REQUEST_CANCEL', 'REQUEST_CANCEL');
define ('EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED', 'ESTIMATION_ASSIGNED');
define ('EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL', 'HOME_OWNER_SCHEDULE_APPROVAL');
define ('EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL', 'ESTIMATOR_SCHEDULE_APPROVAL');
define ('EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH', 'ESTIMATION_READY_FOR_DISPATCH');
define ('EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED', 'ESTIMATION_DISPATCHED');
define ('EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS', 'TENDER_IN_PROGRESS');
define ('EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL', 'TENDER_READY_FOR_SITE_ADMIN_APPROVAL');
define ('EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL', 'TENDER_READY_FOR_HOME_OWNER_APPROVAL');
define ('EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS', 'TENDER_READY_TO_BIDS');
define ('EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED', 'TENDER_TO_BE_MODIFIED');
define ('EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED', 'TENDER_BIDDING_SCHEDULED');
define ('EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS', 'TENDER_OPEN_TO_BIDS');
define ('EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS', 'TENDER_CLOSED_TO_BIDS');
define ('EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION', 'TENDER_OPEN_FOR_BID_SELECTION');
define ('EJQ_DEMAND_STATUS_BID_SELECTED', 'BID_SELECTED');
define ('EJQ_DEMAND_STATUS_BID_DISCLOSED', 'BID_DISCLOSED');
define ('EJQ_DEMAND_STATUS_CONTRACT_SIGNED', 'CONTRACT_SIGNED');
define ('EJQ_DEMAND_STATUS_JOB_IN_PROGRESS', 'JOB_IN_PROGRESS');
define ('EJQ_DEMAND_STATUS_JOB_COMPLETED', 'JOB_COMPLETED');
define ('EJQ_DEMAND_STATUS_CLOSED', 'TENDER_CLOSED');

define ('EJQ_TENDER_FILE_PHASE_BEFORE_TENDER', 'BEFORE');
define ('EJQ_TENDER_FILE_PHASE_AFTER_JOB', 'AFTER');

define ('EJQ_TENDER_FILE_TYPE_DOC', 'DOC');
define ('EJQ_TENDER_FILE_TYPE_IMAGE', 'IMAGE');

define ('EJQ_FOLDER_TENDER_PHOTOS', '/files/tenders/:id:/photos');

define ('EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_READY_FOR_REVIEW', 'HOME_OWNER_TENDER_READY_FOR_REVIEW');
define ('EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_POSTED_FOR_BIDDING', 'HOME_OWNER_TENDER_POSTED_FOR_BIDDING');
define ('EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_EXTENDED_FOR_BIDDING', 'HOME_OWNER_TENDER_EXTENDED_FOR_BIDDING');
define ('EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_OPEN_FOR_SELECTION', 'HOME_OWNER_TENDER_OPEN_FOR_SELECTION');
define ('EJQ_EMAIL_TYPES_CONTRACTOR_QUALIFIED_FOR_BIDDING', 'CONTRACTOR_QUALIFIED_FOR_BIDDING');
define ('EJQ_EMAIL_TYPES_CONTRACTOR_TENDER_EXTENDED_FOR_BIDDING', 'CONTRACTOR_TENDER_EXTENDED_FOR_BIDDING');
define ('EJQ_EMAIL_TYPES_CONTRACTOR_CHOSEN_FOR_JOB', 'CONTRACTOR_CHOSEN_FOR_JOB');

define ('EJQ_BID_STATUS_VISITED', 'VISITED');
define ('EJQ_BID_STATUS_IN_PROGRESS', 'IN_PROGRESS');
define ('EJQ_BID_STATUS_SUBMITTED', 'SUBMITTED');
define ('EJQ_BID_STATUS_CHOSEN', 'CHOSEN');
define ('EJQ_BID_STATUS_CANCEL', 'CANCEL');

define ('EJQ_JOB_STATUS_COMPLETED', 'COMPLETED');
define ('EJQ_JOB_STATUS_IN_PROGRESS', 'IN_PROGRESS');
define ('EJQ_JOB_STATUS_CANCEL', 'CANCEL');

define ('EJQ_ROLE_ADMIN', 'SITE_ADMIN');
define ('EJQ_ROLE_ESTIMATOR', 'ESTIMATOR');
define ('EJQ_ROLE_CONTRACTOR', 'CONTRACTOR');
define ('EJQ_ROLE_VISITOR', 'VISITOR');
define ('EJQ_ROLE_HOME_OWNER', 'HOME_OWNER');
define ('EJQ_ROLE_SYSADMIN', 'SYSTEM_ADMIN');

define ('EJQ_INVOICE_STATUS_DRAFT', 'DRAFT');
define ('EJQ_INVOICE_STATUS_SENT', 'SENT');
define ('EJQ_INVOICE_STATUS_PAID', 'PAID');
define ('EJQ_INVOICE_STATUS_CANCEL', 'CANCEL');

define ('EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT', 'TENDER_DEVELOPMENT');
define ('EJQ_INVOICE_TYPE_COMMISSION', 'COMMISSION');
define ('EJQ_INVOICE_TYPE_OTHER', 'OTHER');


/***
 * CONSTANTS
 */
define ('SUPERUSERS_GROUP', '1');
define ('USERS_GROUP', '2');


define ('REGISTER_UNCONFIRMED', '0');
define ('REGISTER_CONFIRMED', '1');

define ('REGISTER_STATUS_EXISTING', 'EXISTING');
define ('REGISTER_STATUS_NEW', 'NEW');
define ('REGISTER_STATUS_UNCONFIRMED', 'UNCONFIRMED');

define ('META_MARKETPLACE_STATUS_NIL', NULL);
define ('META_MARKETPLACE_STATUS_PUBLISHED', 'PUBLISHED');

define ('DEMAND_STATUS_NIL', NULL);
define ('DEMAND_STATUS_OPEN', 'OPEN');
define ('DEMAND_STATUS_ACCEPTED', 'ACCEPTED');
define ('DEMAND_STATUS_ASSIGNED', 'ASSIGNED');
define ('DEMAND_STATUS_HIRED', 'HIRED');
define ('DEMAND_STATUS_SUPPLIED', 'SUPPLIED');
define ('DEMAND_STATUS_PAID', 'PAID');
define ('DEMAND_STATUS_CLOSED', 'CLOSED');
define ('DEMAND_STATUS_CANCELED', 'CANCELED');

define ('CRITERION_ONLINE', 'online');
define ('CRITERION_QUALIFIED', 'qualified');
define ('CRITERION_SCHEDULED', 'scheduled');
define ('CRITERION_WEEKDAYS', 'weekdays');

define ('FOLDER_COVER', '/img/cover');
define ('FOLDER_LOGO', '/img/logo');
