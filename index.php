<?php

session_start();

require 'vendor/autoload.php';

require 'src/helpers.php';

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
		$app->view->appendData(['photoInfo' => 'Square photo for best results (min. 160 x 160)']);
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
				if(! empty($user->fname) || ! empty($user->fname)) {
					$_SESSION['adminName'] = $user->fname . ' ' . $user->lname;
				} else {
					$_SESSION['adminName'] = $user->username;
				}
				$_SESSION['adminPhoto'] = $user->photo;

				// I need to set these variables here for the first time
				$app->view->appendData(['adminName' => $_SESSION['adminName']]);
				$app->view->appendData(['adminPhoto' => $_SESSION['adminPhoto']]);
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

	// Log out
	$app->get('/logout', function() use ($app){

		unset($_SESSION['adminName']);
		unset($_SESSION['adminPhoto']);

		$app->redirect('../'); // back to home
	})->name('logout');

	// Log in
	$app->get('/login', function() use ($app) {
		echo $app->render('login.html.twig', ['form_action_link'=> $app->urlFor('admin')]);
	})->name('login');

	// Users Routes Group
	$app->group('/users', function () use ($app) {

		$app->get( '/', function () use ( $app ) {
			$users = ORM::for_table( 'users' )->find_many();

			echo $app->render( 'admin/users.html.twig', [ 'users' => $users ] );
		} )->name( 'users' );

		$app->post( '/', function () use ( $app ) {

			$user = ORM::for_table('users')->create();

			$user->username = $app->request->params('userName');
			$user->email = $app->request->params('userEmail');
			$user->pwd = $app->request->params('userPassword');
			$user->fname = $app->request->params('userFname');
			$user->lname = $app->request->params('userLname');
			if ($_FILES['userPhoto']['size'] == 0){
				$user->photo = 'no_photo.jpg';
			} else {
				$user->photo = $_FILES["userPhoto"]["name"];

				$uploadFile = $_SERVER['DOCUMENT_ROOT'] . $app->request->getRootUri() . '/photos/' . $_FILES['userPhoto']['name'];
				$upload = move_uploaded_file($_FILES["userPhoto"]["tmp_name"], $uploadFile);
			}
			$user->phone = $app->request->params('userPhone');

			$user->save();


			if(empty($_FILES['userPhoto']['name']) ) {
				$app->flash( 'success', 'User Created' );
			} elseif ( ! empty($_FILES['userPhoto']['name']) && $_FILES['userPhoto']['error'] != 0) {
				$app->flash( 'fail', 'Photo Upload failed' );
			}
			$app->redirect( './users' );
		} );

		$app->delete( '/', function () use ( $app ) {
			$user = ORM::for_table('users')->find_one($app->request->params('user-id'));
			$user->delete();
			$app->flash( 'success', 'User Deleted' );
			$app->redirect( './users' );
		} );

		$app->post( '/edit-profile', function () use ( $app ) {

			$user = ORM::for_table('users')->find_one($app->request->params('userId'));

			if(! empty($app->request->params('userFname')) ) {
				$_SESSION['adminName'] = $user->fname;
				if (! empty($app->request->params('userLname')) ){
					$_SESSION['adminName'] = $user->fname . ' ' . $user->lname;
				}
			} else {
				$_SESSION['adminName'] = $user->username;
			}

			$user->set([
				'username'  =>  $app->request->params('userName'),
				'email'  =>  $app->request->params('userEmail'),
				'phone'  =>  $app->request->params('userPhone'),
				'fname'  =>  $app->request->params('userFname'),
				'lname'  =>  $app->request->params('userLname'),
			]);
			$user->save();

			$app->flash( 'success', 'Profile Edited' );
			$app->redirect( '../../admin/users' );
		});

		$app->post( '/password', function () use ( $app ) {

			$user = ORM::for_table('users')->find_one($app->request->params('userId'));
			$user->set([
				'pwd'  =>  $app->request->params('confirmPwd'),
			]);
			$user->save();

			$app->flash( 'success', 'Password Updated' );
			$app->redirect( '../../admin/users' );
		});

		$app->post( '/change-photo', function () use ( $app ) {

			$user = ORM::for_table('users')->find_one($app->request->params('userId'));
			$user->set([
				'photo'  =>  $_FILES["userPhoto"]["name"],
			]);
			$user->save();

			$uploadFile = $_SERVER['DOCUMENT_ROOT'] . $app->request->getRootUri() . '/photos/' . $_FILES['userPhoto']['name'];
			$upload = move_uploaded_file($_FILES["userPhoto"]["tmp_name"], $uploadFile);

			$app->flash( 'success', 'Photo Changed' );
			$app->redirect( '../../admin/users' );
		});


	});

	// photos
	$app->group('/photos', function() use ($app) {
		$app->get('/', function() use ($app){
			$albumName = 'bathroom';
			$slider = getSliderPhotos();
			$gallery = getGalleryPhotos($albumName);

			echo $app->render('admin/photos.html.twig', [
				'gallery' => $gallery,
				'slider' => $slider->as_array(),
				'album' => $albumName
			]);
		})->name('photos');

		$app->post('/', function() use ($app) {

			$albumName = $app->request->params('album');
			$slider = getSliderPhotos();
			$gallery = getGalleryPhotos($albumName);

			if(isset($_FILES['gallery-photo']) && $_FILES['gallery-photo']['size'] > 0){
				$photo = ORM::for_table('photos')->create();
				$photo->set([
					'name'  =>  $_FILES["gallery-photo"]["name"],
					'album' =>  $albumName
				]);
				$photo->save();

				$uploadFile = $_SERVER['DOCUMENT_ROOT'] . $app->request->getRootUri() . '/photos/' . $_FILES['gallery-photo']['name'];
				move_uploaded_file($_FILES["gallery-photo"]["tmp_name"], $uploadFile);
			}



			echo $app->render('admin/photos.html.twig', [
				'gallery' => $gallery,
				'slider' => $slider->as_array(),
				'album' => $albumName
			]);


		});

		$app->delete('/', function() use ($app) {
			$option = $app->request->params('option');

			if ($option == 'add') {
				$photo = ORM::for_table('photos')->find_one($app->request->params('gallery-photo'));
				$photo->slider = 1;
				$photo->save();
				$app->flash( 'success', 'Photo Added To Slider' );

			} elseif ($option == 'remove') {
				$photo = ORM::for_table('photos')->find_one($app->request->params('gallery-photo'));
				$photo->slider = 0;
				$photo->save();
				$app->flash( 'success', 'Photo Removed From Slider' );

			} else {
				$galleryPhotoId = ORM::for_table('photos')->find_one($app->request->params('gallery-photo'));
				$galleryPhotoId->delete();
				$app->flash( 'success', 'Photo Deleted' );

			}

			$app->redirect( './photos' );
		});
	});


	// Testimonials
	$app->group('/testimonials', function () use ($app) {

		// Get All testimonials
		$app->get('/', function() use ($app){
			$testimonials = ORM::for_table('testimonials')->find_many();
			$data['testimonials'] = $testimonials;
			echo $app->render('admin/testimonials.html.twig', $data);
		})->name('testimonials');

		// New testimonial
		$app->post('/', function() use ($app){
			$testimonial = ORM::for_table('testimonials')->create();
			$testimonial->title = $app->request->params('testimonialTitle');
			$testimonial->body = $app->request->params('testimonialBody');
			$testimonial->owner = $app->request->params('testimonialOwner');
			$testimonial->company = $app->request->params('testimonialCompany');
			if ($_FILES['testimonialPhoto']['size'] == 0){
				$testimonial->photo = 'no_photo.jpg';
			} else {
				$testimonial->photo = $_FILES["testimonialPhoto"]["name"];

				$uploadFile = $_SERVER['DOCUMENT_ROOT'] . $app->request->getRootUri() . '/photos/' . $_FILES['testimonialPhoto']['name'];
				$upload = move_uploaded_file($_FILES["testimonialPhoto"]["tmp_name"], $uploadFile);
			}
			$testimonial->save();

			$app->redirect('testimonials');
		});

		// Edit testimonial
		$app->post('/edit-testimonial', function() use ($app) {
			$user = ORM::for_table('testimonials')->find_one($app->request->params('testimonialId'));


			$user->set([
				'title'  =>  $app->request->params('testimonialTitle'),
				'body'  =>  $app->request->params('testimonialBody'),
				'owner'  =>  $app->request->params('testimonialOwner'),
				'company'  =>  $app->request->params('testimonialCompany'),
			]);
			$user->save();

			$app->flash( 'success', 'Testimonial Edited' );
			$app->redirect( '../../admin/testimonials' );
		});

		// Edit photo
		$app->post( '/change-photo', function () use ( $app ) {

			$user = ORM::for_table('testimonials')->find_one($app->request->params('userId'));
			$user->set([
				'photo'  =>  $_FILES["changeTestimonialPhoto"]["name"],
			]);
			$user->save();

			$uploadFile = $_SERVER['DOCUMENT_ROOT'] . $app->request->getRootUri() . '/photos/' . $_FILES['changeTestimonialPhoto']['name'];
			$upload = move_uploaded_file($_FILES["changeTestimonialPhoto"]["tmp_name"], $uploadFile);

			$app->flash( 'success', 'Photo Changed' );
			$app->redirect( '../../admin/testimonials' );
		});

		// Delete testimonials with ID
		$app->delete('/', function() use ($app) {
			$testimonial = ORM::for_table('testimonials')->find_one($app->request->params('testimonial-id'));
			$testimonial->delete();
			$app->flash( 'success', 'Testimonial Deleted' );
			$app->redirect( './testimonials' );


		});

	});

});




$app->run();