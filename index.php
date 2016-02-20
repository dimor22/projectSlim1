<?php
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
 * TWIG VIEWS - Extension
 */
$view = $app->view();
$view->parserOptions = [ 'debug' => true ];
$view->parserExtensions = [ new \Slim\Views\TwigExtension() ];


// 404 not found
$app->notFound(function () use ($app) {
	$app->render('404.html.twig');
});

// Session
$app->add(new \Slim\Middleware\SessionCookie(array(
	'expires' => '30 minutes',
	'path' => '/',
	'domain' => null,
	'secure' => false,
	'httponly' => false,
	'name' => 'slim_session',
	'secret' => 'blablabla',
	'cipher' => MCRYPT_RIJNDAEL_256,
	'cipher_mode' => MCRYPT_MODE_CBC
)));


// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
	$app->config(array(
		'log.enable' => true,
		'debug' => false
	));
});




// Routes

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
$app->group('/admin', function () use ($app, $twig) {


	$app->get('/', function() use ($app) {
		echo $app->redirect('admin/login');
	})->name('admin');

	/**
	 * Entrance Route to the Admin Dashboard
	 */
	$app->post('/', function() use ($app, $twig) {
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
				$app->view->appendData(['adminName' => $_SESSION['adminName']]);
				$app->view->appendData(['adminPhoto' => $_SESSION['adminPhoto']]);
			} else {
				$app->flash('error', 'Invalid Password');
			}
		} else {
			$app->flash('error', 'User Not Found');
		}

		// TODO Drop cookie to keep admin logged in


		// Render View
		if ( $is_admin ) {
			echo $app->render('admin/dashboard.html.twig');
		} else {
			$app->redirect('login');
		}
	});

	$app->get('/logout', function() use ($app){
		// TODO delete admin cookie
		$app->redirect('../'); // back to home
	})->name('logout');

	$app->get('/login', function() use ($app) {
		echo $app->render('login.html.twig', ['form_action_link'=> $app->urlFor('admin'), 'flash' => $_SESSION['slim.flash']]);
	})->name('login');

	$app->get('/users', function() use ($app){
		$users = ORM::for_table('users')->find_many();
		echo $app->render('admin/users.html.twig', ['users'=> $users]);
	})->name('users');

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

/**
 * Global View Variables
 */

// Base static links (header, sidebar, footer)
//$twig->addGlobal('testimonials_link', $app->urlFor('testimonials'));
//$twig->addGlobal('logout_link', $app->urlFor('logout'));
//$twig->addGlobal('users_link', $app->urlFor('users'));
//$twig->addGlobal('admin2', 'David Lopez');


$app->run();