<?php
	
	namespace CodeIgniter\Commands\Utilities\Routes;
	
	use CodeIgniter\Router\RouteCollection;
	
	/**
	 * @var RouteCollection $routes
	 */
	$routes->setDefaultNamespace ( 'App\Controllers' );
	$routes->setTranslateURIDashes ( FALSE );
	$routes->setDefaultController ( 'Home' );
	$routes->setDefaultMethod ( 'index' );
	$routes->setAutoRoute ( FALSE );
	//====================================||  Rutas  ||====================================
	//====================================|| Webhook ||====================================
	//====================================|| Session ||====================================
	$routes->get ( 'forgot', 'ProfileController::forgot' /**@uses \App\Controllers\ProfileController::forgot * */ );
	$routes->get ( 'signout', 'SessionController::signOut' /**@uses \App\Controllers\SessionController::signOut * */ );
	$routes->get ( 'signin', 'SigninController::index' /**@uses \App\Controllers\SigninController::index * */ );
	//====================================||   GET   ||====================================
	$routes->get ( '/', 'BlueBullController::index' /**@uses \App\Controllers\Home::index * */ );
	//====================================||   POST  ||====================================
	$routes->post ( 'signin', 'SigninController::signIn' /**@uses \App\Controllers\SigninController::signIn * */ );
	$routes->post ( 'searchRfc', 'BlueBullController::searchRfc' /**@uses \App\Controllers\BlueBullController::searchRfc * */ );
	//====================================||   PUT   ||====================================
	//====================================||  PATCH  ||====================================
	//====================================|| DELETE  ||====================================
	//====================================||   END   ||====================================
