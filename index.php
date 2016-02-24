<?php

session_start();

require 'vendor/autoload.php';

date_default_timezone_set('America/Los_Angeles');


/**
 * IDIORM - ORM
 */
require_once 'vendor/idiorm/idiorm.php';
require_once 'db_config.php';

/**
 * TWIG - Template System
 */
$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);


/**
 * SLIM - Config
 */
$app = new \Slim\Slim([
	'debug' => true,
	'mode' => 'development',
	'templates.path' => './templates',
	'log.enable' => false,
	'view' => new \Slim\Views\Twig()
]);

/**
 * Pass global variables to parent views
 */
$app->hook('slim.before', function () use ($app) {
	if ( isset($_SESSION['adminName'])) {
		$app->view->appendData(['adminName' => $_SESSION['adminName']]);
		$app->view->appendData(['adminPhoto' => $_SESSION['adminPhoto']]);
	}
});


/**
 * TWIG VIEWS - Extension
 */
$view = $app->view();
$view->parserOptions = [ 'debug' => true ];
$view->parserExtensions = [ new \Slim\Views\TwigExtension() ];


// 404 not found
$app->notFound(function () use ($app) {
	$app->render('404.html.twig');
});


// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
	$app->config(array(
		'log.enable' => true,
		'debug' => false
	));
});


/**
 * ALL APP ROUTES
 */

$app->get('/', function() use ($app, $twig) {
	echo $app->render('home.html.twig');
})->name('home');

$app->get('/hello/:name', function ($name) {
	echo "Hello, $name";
});

$app->get('/contact', function () {
	echo "This is the Contact Page";
});

$app->get('/appointments', function () {
	echo "This is the Appointments Page";
});

$app->get('/about', function () {
	echo "This is the About Page";
});

$app->get('/products', function () {
	echo "This is the Products Page";
});

$app->get('/services', function () {
	echo "This is the Services Page";
});

$app->get('/testimonials', function () {
	echo "This is the Testimonials Page";
});

$app->get('/gallery', function () {
	echo "This is the Photo Gallery Page";
});


// Admin routes
$app->group('/admin', function () use ($app) {


	$app->get('/', function() use ($app) {
		if ( isset($_SESSION['adminName']) ) {
			$app->redirect('admin/dashboard');
		} else {
			$app->redirect('admin/login');
		}
	})->name('admin');

	$app->get('/dashboard', function() use ($app) {
		echo $app->render('admin/dashboard.html.twig');
	})->name('dashboard');

	/**
	 * Entrance Route to the Admin Dashboard
	 */
	$app->post('/', function() use ($app) {
		// Get User
		$username = $app->request()->params('username');
		$password = $app->request()->params('pwd');
		$user = ORM::for_table('users')->where('username', $username)->find_one();

		// Validate User and Set Errors
		$is_admin = false;
		if ( $user ) {
			if ( $user->pwd == $password ) {
				$is_admin = true;
				$_SESSION['adminName'] = $user->fname . ' ' . $user->lname;
				$_SESSION['adminPhoto'] = $user->photo;
			} else {
				$app->flash('error', 'Invalid Password');
			}
		} else {
			$app->flash('error', 'User Not Found');
		}

		// Render View
		if ( $is_admin ) {
			echo $app->render('admin/dashboard.html.twig');
		} else {
			$app->redirect('login');
		}
	});
	$app->get('/logout', function() use ($app){

		unset($_SESSION['adminName']);
		unset($_SESSION['adminPhoto']);

		$app->redirect('../'); // back to home
	})->name('logout');

	$app->get('/login', function() use ($app) {
		echo $app->render('login.html.twig', ['form_action_link'=> $app->urlFor('admin')]);
	})->name('login');

	$app->group('/users', function () use ($app) {

		$app->get( '/', function () use ( $app ) {
			$users = ORM::for_table( 'users' )->find_many();

			echo $app->render( 'admin/users.html.twig', [ 'users' => $users ] );
		} )->name( 'users' );

		$app->post( '/', function () use ( $app ) {
//			var_dump($app->request->params());
//			var_dump($_FILES);
//			var_dump(move_uploaded_file($_FILES["userPhoto"]["name"], "$app->request->getRootUri() . /photos/"));
//			die;

			$user = ORM::for_table('users')->create();

			$user->username = $app->request->params('userName');
			$user->email = $app->request->params('userEmail');
			$user->pwd = $app->request->params('userPassword');
			$user->fname = $app->request->params('userFname');
			$user->lname = $app->request->params('userLname');
			$user->photo = $_FILES["userPhoto"]["name"];
			$user->phone = $app->request->params('userPhone');

			$user->save();

			// TODO save image in server


			$app->flash( 'success', 'User Created' );
			$app->redirect( './users' );
		} );

		$app->delete( '/', function () use ( $app ) {
			$user = ORM::for_table('users')->find_one($app->request->params('user-id'));
			$user->delete();
			$app->flash( 'success', 'User Deleted' );
			$app->redirect( './users' );
		} );

		$app->post( '/edit-profile', function () use ( $app ) {

			$app->flash( 'success', 'Profile Edited' );
			$app->redirect( '../../admin/users' );
		});

		$app->post( '/password', function () use ( $app ) {

			$app->flash( 'success', 'Password Updated' );
			$app->redirect( '../../admin/users' );
		});
	});

	$app->get('/testimonials', function() use ($app){
		$testimonials = ORM::for_table('testimonials')->find_many();
		$data['testimonials'] = $testimonials;
		echo $app->render('admin/testimonials.html.twig', $data);
	})->name('testimonials');

	$app->get('/photos', function() use ($app){
		$photos = ORM::for_table('photos')->find_many();
		echo $app->render('admin/photos.html.twig');
	})->name('photos');


	// Testimonials
	$app->group('/testimonials', function () use ($app) {

		// Get testimonials with ID
		$app->get('/:id', function ($id) {

			$testimonial = ORM::for_table('testimonials')->find_one($id);

			echo "This GETS testimonial with id $id<br/>";

			if ($testimonial) {
				var_dump($testimonial);
			} else {
				echo "Testimonial with id $id not doesn't exist.";
			}
		});

		// Post testimonials with ID
		$app->post('/:id', function ($id) {
			echo "This POSTS testimonial with id $id";
		});

		// Update testimonials with ID
		$app->put('/:id', function ($id) {
			echo "This PUTS testimonial with id $id";
		});

		// Delete testimonials with ID
		$app->delete('/:id', function ($id) {
			echo "This DELETES testimonial with id $id";
		});

	});

});




$app->run();