<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);
Router::extensions(['xml','csv','txt','pdf', 'html']);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
	$routes->connect('/', ['controller' => 'Teams', 'action' => 'index']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
	$routes->connect('/index/*', ['controller' => 'Teams', 'action' => 'index']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
//    $routes->fallbacks(DashedRoute::class);
});


Router::connect('/', array('controller' => 'teams','action'=>'index'));
Router::connect('/login', array('controller' => 'users','action'=>'login'));
Router::connect('/register_step1', array('controller' => 'users','action'=>'register1'));
Router::connect('/register', array('controller' => 'users','action'=>'register1'));
Router::connect('/register_step2', array('controller' => 'users','action'=>'register2'));
Router::connect('/index', array('controller' => 'users','action'=>'index'));
Router::connect('/setPassword', array('controller' => 'users', 'action'=>'setPassword'));

Router::scope('/', function ($routes) {
	$routes->resources('Teams', function ($routes) {
		$routes->resources('Events', function () {
			Router::connect('teams/:team_id/events/:action/*', array('controller' => 'events'));
		});
		$routes->resources('Places', function ($routes) {
			Router::connect('teams/:team_id/places/', array('controller' => 'Places', 'action'  => 'index'));
			Router::connect('teams/:team_id/places/:action/*', array('controller' => 'Places'));
		});
		$routes->resources('Fees', function ($routes) {
			Router::connect('teams/:team_id/fees/', array('controller' => 'Fees', 'action'  => 'index'));
			Router::connect('teams/:team_id/fees/:action/*', array('controller' => 'Fees'));
		});
		$routes->resources('UsersFees', function ($routes) {
			Router::connect('teams/:team_id/addFeeToMember/:action/*', array('controller' => 'UsersFees'));
		});
		$routes->resources('TeamMembers', function ($routes) {
			Router::connect('teams/:team_id/members/', array('controller' => 'TeamMembers', 'action'  => 'index'));
			$routes->resources('UserFees', function () {
				Router::connect('teams/:team_id/members/:user_id/fees/:action/*', array('controller' => 'UsersFees'));
			});
			Router::connect('teams/:team_id/members/:action/*', array('controller' => 'TeamMembers'));
		});
	});
	Router::connect('/teams/:action/*', array('controller' => 'teams'));
});

/*
 * API calls
 */
Router::connect('/usersApi/:action/*', array('controller' => 'usersApi'));
Router::connect('/teamsApi/:action/*', array('controller' => 'teamsApi'));
Router::connect('/teamMembersApi/:action/*', array('controller' => 'teamMembersApi'));
Router::connect('/FeesApi/:action/*', array('controller' => 'FeesApi'));
Router::connect('/eventsApi/:action/*', array('controller' => 'eventsApi'));
Router::connect('/placesApi/:action/*', array('controller' => 'placesApi'));
Router::connect('/notificationApi/:action/*', array('controller' => 'notificationApi'));
Router::connect('/usersFeesApi/:action/*', array('controller' => 'usersFeesApi'));
Router::connect('/paymentsApi/:action/*', array('controller' => 'paymentsApi'));
/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
