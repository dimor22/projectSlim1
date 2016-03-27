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
	$app->view->appendData(['header_email' => 'info@novainteriorslv.com']);
	$app->view->appendData(['header_phone' => '702-241-1491']);

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
		'debug' => true
	));
});


/**
 * ALL APP ROUTES
 */

$app->get('/', function() use ($app, $twig) {
	$pageData = [
		'title'     => 'Home Page',
		'slides'    => [
			'assets/images/corporate/top-slider/photo1.jpg',
			'assets/images/corporate/top-slider/photo2.jpg',
			'assets/images/corporate/top-slider/photo3.jpg',
			'assets/images/corporate/top-slider/photo4.jpg',
		],
		'work'      => [
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo1.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo1.jpg',
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo2.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo2.jpg',
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo3.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo3.jpg',
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo4.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo4.jpg',
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo5.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo5.jpg',
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo6.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo6.jpg',
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo7.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo7.jpg',
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => 'assets/images/corporate/latest-work/photo8.jpg',
				'alt'           => 'NOVA INTERIORS',
				'src'           => 'assets/images/corporate/latest-work/photo8.jpg',
				'h3'            => 'NOVA INTERIORS'
			]
		],
		'team'      => [
			[
				'delay' =>  '0.1s',
				'alt'   =>  'Brandon Reed',
				'src'   =>  'assets/images/corporate/team01-notinclude.jpg',
				'social'    =>  [
					[
					'name'  =>  'facebook',
					'href'  =>  '#'
					],
					[
						'name'  =>  'twitter',
						'href'  =>  '#'
					],
					[
						'name'  =>  'behance',
						'href'  =>  '#'
					]
				],
				'strong'    =>  'Brandon Reed',
				'span'      =>  'CEO',
				'p'         =>  ' Collaboratively administrate empowered markets via plug-and-play networks. Dynamically procrastinate users.'
			],
			[
				'delay' =>  '0.2s',
				'alt'   =>  'Amanda Hayes',
				'src'   =>  'assets/images/corporate/team02-notinclude.jpg',
				'social'    =>  [
					[
						'name'  =>  'facebook',
						'href'  =>  '#'
					],
					[
						'name'  =>  'twitter',
						'href'  =>  '#'
					],
					[
						'name'  =>  'behance',
						'href'  =>  '#'
					]
				],
				'strong'    =>  'Amanda Hayes',
				'span'      =>  'Director',
				'p'         =>  ' Efficiently unleash cross-media information without cross-media value. Quickly maximize deliverables schemas.'
			],
			[
				'delay' =>  '0.3s',
				'alt'   =>  'Donald Coleman',
				'src'   =>  'assets/images/corporate/team03-notinclude.jpg',
				'social'    =>  [
					[
						'name'  =>  'facebook',
						'href'  =>  '#'
					],
					[
						'name'  =>  'twitter',
						'href'  =>  '#'
					],
					[
						'name'  =>  'behance',
						'href'  =>  '#'
					]
				],
				'strong'    =>  'Donald Coleman',
				'span'      =>  'Developer',
				'p'         =>  ' Completely synergize resource sucking relationships premier niche markets. Professionally cultivate customer.'
			]
		],
		'testimonials_info'  => [

			[
				'h1'    =>  'Lisa Hughes',
				'src'   => 'assets/images/corporate/team16-notinclude.jpg',
				'alt'   =>  'team16-notinclude',
				'cite'  =>  'Technolab'
			],
			[
				'h1'    =>  'Marie Clark',
				'src'   => 'assets/images/corporate/team15-notinclude.jpg',
				'alt'   =>  'team15-notinclude',
				'cite'  =>  'Technolab'
			],
			[
				'h1'    =>  'Scott Adams',
				'src'   => 'assets/images/corporate/team13-notinclude.jpg',
				'alt'   =>  'team13-notinclude',
				'cite'  =>  'Trisbam'
			]

		],
		'testimonial_text'  =>  [
				'This theme is super easy to customise and the support team is just awesome. They helped me add a few design and styling changes and guided me to customise my theme. They were very knowledgable and fast. Thanks again! This is the best theme and support money can buy!',
				'After purchasing all of the top rated theme I find this theme to be the most intuitive. Everything is right where it\'s supposed to be and makes use of the best plugin combinations I have ever seen. Truly, there are no words to describe how happy I am with this theme! Recommended',
				'This Theme is just awesome. It is so easy to create a gorgeous looking sites. I would also like to mention their excellent and superfast support. It never took more than 2-3hours to have the correct solution. I\'m going to buy more themes of them, sure! Keep up the good work.'
		]
	];
	//$me = ORM::for_table('users')->find_one(1);
	echo $app->render('public_base.twig', ['data' => $pageData, 'state1'  => 'active']);
})->name('home');

$app->get('/contact', function () use ($app) {
	$pageData = [
		'title' =>  'Contact Page',
		'team'      => [
			[
				'delay' =>  '0.1s',
				'alt'   =>  'Brandon Reed',
				'src'   =>  'assets/images/corporate/team01-notinclude.jpg',
				'social'    =>  [
					[
						'name'  =>  'facebook',
						'href'  =>  '#'
					],
					[
						'name'  =>  'twitter',
						'href'  =>  '#'
					],
					[
						'name'  =>  'behance',
						'href'  =>  '#'
					]
				],
				'strong'    =>  'Brandon Reed',
				'span'      =>  'CEO',
				'p'         =>  ' Collaboratively administrate empowered markets via plug-and-play networks. Dynamically procrastinate users.'
			],
			[
				'delay' =>  '0.2s',
				'alt'   =>  'Amanda Hayes',
				'src'   =>  'assets/images/corporate/team02-notinclude.jpg',
				'social'    =>  [
					[
						'name'  =>  'facebook',
						'href'  =>  '#'
					],
					[
						'name'  =>  'twitter',
						'href'  =>  '#'
					],
					[
						'name'  =>  'behance',
						'href'  =>  '#'
					]
				],
				'strong'    =>  'Amanda Hayes',
				'span'      =>  'Director',
				'p'         =>  ' Efficiently unleash cross-media information without cross-media value. Quickly maximize deliverables schemas.'
			],
			[
				'delay' =>  '0.3s',
				'alt'   =>  'Donald Coleman',
				'src'   =>  'assets/images/corporate/team03-notinclude.jpg',
				'social'    =>  [
					[
						'name'  =>  'facebook',
						'href'  =>  '#'
					],
					[
						'name'  =>  'twitter',
						'href'  =>  '#'
					],
					[
						'name'  =>  'behance',
						'href'  =>  '#'
					]
				],
				'strong'    =>  'Donald Coleman',
				'span'      =>  'Developer',
				'p'         =>  ' Completely synergize resource sucking relationships premier niche markets. Professionally cultivate customer.'
			]
		],
	];
	echo $app->render('contact.twig', ['data'   =>  $pageData, 'state3'  => 'active']);
})->name('contact');

$app->get('/appointments', function () use ($app) {
	$pageData = [
		'title' =>  'Appointments Page'
	];

	$app->render('appointments.twig', ['data'   =>  $pageData, 'state7'  => 'active']);
});

$app->get('/about', function () use ($app) {
	$pageData = [
		'title' =>  'About Page'
	];
	echo $app->render('about.twig', ['data'   =>  $pageData, 'state2'  => 'active']);
})->name('about');

$app->get('/products', function () use ($app) {
	$pageData = [
		'title' =>  'Products Page'
	];

	$app->render('404.html.twig', ['data'   =>  $pageData, 'state8'  => 'active']);
});

$app->get('/services', function () use ($app) {
	$pageData = [
		'title' =>  'Services Page'
	];
	$app->render('services.twig', ['data'   =>  $pageData, 'state4'  => 'active']);
});

$app->get('/testimonials', function () use ($app) {
	$pageData = [
		'title' =>  'Testimonial Page'
	];
	$app->render('testimonials.twig',  ['data'   =>  $pageData, 'state5'  => 'active']);
});

$app->get('/gallery', function () use ($app) {
	$pageData = [
		'title' =>  'Gallery Page'
	];
	$app->render('gallery.twig', ['data'   =>  $pageData, 'state6'  => 'active']);
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
	 * Entrance Route to the ADMIN Dashboard
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

	// Users
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

//			if(! empty($app->request->params('userFname')) ) {
//				$_SESSION['adminName'] = $user->fname;
//				if (! empty($app->request->params('userLname')) ){
//					$_SESSION['adminName'] = $user->fname . ' ' . $user->lname;
//				}
//			} else {
//				$_SESSION['adminName'] = $user->username;
//			}

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

	// Photos
	$app->group('/photos', function() use ($app) {

		$app->get('/', function() use ($app){
			$albumName = 'bathroom';
			$slider = getSliderPhotos();
			$gallery = getGalleryPhotos($albumName);
			$albums = ORM::for_table('albums')->find_many();
			$album = ORM::for_table('albums')->where('name', $albumName)->find_one();
			$beafs = ORM::for_table('beaf')->order_by_desc('created_at')->find_many();

			echo $app->render('admin/photos.html.twig', [
				'gallery' => $gallery,
				'slider' => $slider->as_array(),
				'album' => $album->as_array(),
				'albums' => $albums,
				'beafs' =>  $beafs
			]);
		})->name('photos');

		$app->post('/', function() use ($app) {

			$albumName = $app->request->params('album');
			$album = ORM::for_table('albums')->where('name', $albumName)->find_one();
			$beafs = ORM::for_table('beaf')->order_by_desc('created_at')->find_many();


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

			$slider = getSliderPhotos();
			$gallery = getGalleryPhotos($albumName);
			$albums = ORM::for_table('albums')->find_many();


			echo $app->render('admin/photos.html.twig', [
				'gallery' => $gallery,
				'slider' => $slider->as_array(),
				'album' => $album,
				'albums' => $albums,
				'beafs' =>  $beafs


			]);


		})->name('addAlbum');

		$app->post('/newalbum', function() use ($app){
			$newAlbumName = $app->request->params('newalbum');

			$newAlbum = ORM::for_table('albums')->create();
			$newAlbum->name = $newAlbumName;
			$newAlbum->save();

			$app->redirect( '../../admin/photos' );
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

		$app->delete('/album', function() use ($app){
			$album = ORM::for_table('albums')->find_one($app->request->params('album-id'));
			$albumName = $album->name;
			$album->delete();
			$photos = ORM::for_table('photos')->where('album', $albumName)->find_many();
			$photos->delete();

			$app->flash( 'success', 'Album Deleted' );
			$app->redirect('../../admin/photos');
		});

		$app->delete('/beaf', function() use ($app){
			$beaf = ORM::for_table('beaf')->find_one($app->request->params('beaf-id'));
			$beaf->delete();

			$app->flash( 'success', 'Before and After Deleted' );
			$app->redirect('../../admin/photos');
		});
	});

	// Testimonials
	$app->group('/testimonials', function () use ($app) {

		// Get All testimonials
		$app->get('/', function() use ($app){
			$testimonials = ORM::for_table('testimonials')->order_by_desc('created_at')->find_many();
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