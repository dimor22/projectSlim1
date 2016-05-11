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
		'debug' => false
	));
});


/**
 * ALL APP ROUTES
 */

$app->get('/', function() use ($app, $twig) {
	$slides = ORM::for_table('photos')->where('slider', 1)->find_array();
	$work = ORM::for_table('photos')->where_like('name', '%work%')->find_array();
	$testimonials = ORM::for_table('testimonials')->order_by_desc('created_at')->find_array();
	$pageData = [
		'title'     => 'Home Page',
		'slides'    => [
			$slides[1]['name'],
			$slides[2]['name'],
			$slides[3]['name'],
			$slides[0]['name']
		],
		'work'      => [
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[0]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[0]['name'],
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[1]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[1]['name'],
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[2]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[2]['name'],
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[3]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[3]['name'],
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[4]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[4]['name'],
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[5]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[5]['name'],
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[6]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[6]['name'],
				'h3'            => 'NOVA INTERIORS'
			],
			[
				'data-title'    => 'NOVA INTERIORS',
				'href'          => $work[7]['name'],
				'alt'           => 'NOVA INTERIORS',
				'src'           => $work[7]['name'],
				'h3'            => 'NOVA INTERIORS'
			]
		],
		'team'      => [
			[
				'delay' =>  '0.1s',
				'alt'   =>  'Alex',
				'src'   =>  'photos/jhovany.jpg',
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
				'strong'    =>  'Alex',
				'span'      =>  'CEO',
				'p'         =>  ' Collaboratively administrate empowered markets via plug-and-play networks. Dynamically procrastinate users.'
			],
			[
				'delay' =>  '0.2s',
				'alt'   =>  'Omar',
				'src'   =>  'photos/omar.jpg',
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
				'strong'    =>  'Omar',
				'span'      =>  'Director',
				'p'         =>  ' Efficiently unleash cross-media information without cross-media value. Quickly maximize deliverables schemas.'
			],
			[
				'delay' =>  '0.3s',
				'alt'   =>  'Juan',
				'src'   =>  'photos/juan.jpg',
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
				'strong'    =>  'Juan',
				'span'      =>  'Developer',
				'p'         =>  ' Completely synergize resource sucking relationships premier niche markets. Professionally cultivate customer.'
			]
		],
		'testimonials'  => $testimonials
	];
	echo $app->render('public_base.twig', ['data' => $pageData, 'state1'  => 'active']);
})->name('home');

$app->get('/contact', function () use ($app) {
	$pageData = [
		'title' =>  'Contact Page',
		'team'      => [
			[
				'delay' =>  '0.1s',
				'alt'   =>  'Alex',
				'src'   =>  'photos/jhovany.jpg',
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
				'strong'    =>  'Alex',
				'span'      =>  'CEO',
				'p'         =>  ' Collaboratively administrate empowered markets via plug-and-play networks. Dynamically procrastinate users.'
			],
			[
				'delay' =>  '0.2s',
				'alt'   =>  'Omar',
				'src'   =>  'photos/omar.jpg',
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
				'strong'    =>  'Omar',
				'span'      =>  'Director',
				'p'         =>  ' Efficiently unleash cross-media information without cross-media value. Quickly maximize deliverables schemas.'
			],
			[
				'delay' =>  '0.3s',
				'alt'   =>  'Juan',
				'src'   =>  'photos/juan.jpg',
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
				'strong'    =>  'Juan',
				'span'      =>  'Developer',
				'p'         =>  ' Completely synergize resource sucking relationships premier niche markets. Professionally cultivate customer.'
			]
		],
	];
	echo $app->render('contact.twig', ['data'   =>  $pageData, 'state3'  => 'active']);
})->name('contact');

$app->post('/contact', function () use ($app) {

	$email = $app->request->params('email');
	$name = $app->request->params('name');
	$message = $app->request->params('message');
	$captcha = $app->request->params('g-recaptcha-response');

	// Send google captcha response

	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array('secret' => '6LfXoh4TAAAAAN3xHCaiB1DrBASB3sx6wXNyglYe', 'response' => $captcha);

	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	$result = json_decode($result);

	if ($result->success === TRUE) {

		require 'vendor/phpmailer/phpmailer/class.phpmailer.php';

		$mail = new PHPMailer;
		$mail->isSendmail();
		$mail->setFrom("info@novainteriorslv.com", "NovaInteriors");
		$mail->addReplyTo($email, $name);
		$mail->addAddress('dimor22@gmail.com', 'Nova Interiors Web Form');
		$mail->addAddress('alex@novainteriorslv.com');
		$mail->addAddress('omar@novainteriorslv.com');
		$mail->addAddress('jortiz@novainteriorslv.com');
		$mail->addAddress('alex@novainteriorslv.com');
		$mail->addAddress('tech@novainteriorslv.com');

		$mail->Subject = 'Message from Nova Interiors Web Form';
		$mail->msgHTML('<ul><li><strong>Name: </strong>'. $name .'</li><li><strong>Email: </strong>'. $email .'</li></ul><p>' . $message . '</p>');
		$mail->AltBody = $message;
		if (!$mail->send()) {
			$app->flash( 'danger', 'Sorry, your message could not be sent at this time.' );
			$app->redirect('contact');
		} else {
			$app->flash( 'form', 'Thank You, your message have been sent.' );
			$app->redirect('contact');
		}

	} else {
		$app->flash( 'danger', 'Sorry, your message could not be sent at this time.' );
		$app->redirect('contact');
	}




});

$app->get('/appointments', function () use ($app) {
	$pageData = [
		'title' =>  'Appointments Page'
	];

	$app->render('appointments.twig', ['data'   =>  $pageData, 'state7'  => 'active']);
})->name('appts');

$app->post('/appointments', function() use ($app){
	$params = $app->request->params();

	$appt = ORM::for_table('appts')->create();
	$appt->date = $params['date'];
	$appt->time = $params['time'];
	$appt->product = $params['product'];
	$appt->name = $params['name'];
	$appt->address = $params['address'];
	$appt->city = $params['city'];
	$appt->state = $params['state'];
	$appt->zip = $params['zip'];
	$appt->phone = $params['phone'];
	$appt->email = $params['email'];
	$appt->save();

	// Send email

	require 'vendor/phpmailer/phpmailer/class.phpmailer.php';

	$mail = new PHPMailer;
	$mail->isSendmail();
	$mail->setFrom("info@novainteriorslv.com", "NovaInteriors");
	$mail->addReplyTo($params['email'], $params['name']);
	$mail->addAddress('dimor22@gmail.com', 'Nova Interiors Website');
	$mail->addAddress('alex@novainteriorslv.com');
	$mail->addAddress('omar@novainteriorslv.com');
	$mail->addAddress('jortiz@novainteriorslv.com');
	$mail->addAddress('alex@novainteriorslv.com');
	$mail->addAddress('tech@novainteriorslv.com');
	$mail->Subject = 'New Appointment from Nova Interiors';
	$mail->msgHTML('<ul><li><strong>Name: </strong>'. $params['name'] .'</li>
						<li><strong>Email: </strong>'. $params['email'] .'</li>
						<li><strong>Phone: </strong>'. $params['phone'] .'</li>
						<li><strong>Address: </strong>'. $params['address'] .'</li>
						<li><strong>City: </strong>'. $params['city'] .'</li>
						<li><strong>State: </strong>'. $params['state'] .'</li>
						<li><strong>Zip: </strong>'. $params['zip'] .'</li>
						<li><strong>Date: </strong>'. $params['date'] .'</li>
						<li><strong>Time: </strong>'. $params['time'] .'</li>
						<li><strong>Product: </strong>'. $params['product'] .'</li></ul>');
	$mail->AltBody = $params['name'] . ' ' . $params['phone'] . ' ' . $params['email'];
	if (!$mail->send()) {
		$app->flash( 'danger', 'Sorry, your message could not be sent at this time.' );
		$app->redirect('contact');
	} else {
		$app->flash( 'form', 'Thank You, your message have been sent.' );
		$app->redirect('contact');
	}
});

$app->get('/appointments/date', function() use ($app){
	$params = $app->request->params();
	$times = [];
	$appts = ORM::for_table('appts')->where('date', $params['date'])->find_many();
	if ($appts->count() > 0 ){
		foreach($appts as $appt) {
			$times[] = $appt->time;
		}
		echo json_encode($times);
	} else {
		echo json_encode(['response'=> 'all available']);
	}
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
	$testimonials = ORM::for_table('testimonials')->order_by_desc('created_at')->find_array();
	$pageData = [
		'title' =>  'Testimonial Page',
		'testimonials'  => $testimonials
	];
	$app->render('testimonials.twig',  ['data'   =>  $pageData, 'state5'  => 'active']);
});

$app->get('/gallery', function () use ($app) {
	$photos = ORM::for_table('photos')->order_by_desc('created_at')->find_array();
	$albums = ORM::for_table('albums')->order_by_asc('name')->find_array();

	$pageData = [
		'title' =>  'Gallery Page',
		'photos' => $photos,
		'albums'    => $albums
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

	// Appointments

	$app->group('/appointments', function () use ($app) {

		$app->get('/', function() use ($app) {
			$appts = ORM::for_table('appts')->order_by_desc('date')->find_array();
			$data['appts'] = $appts;
			echo $app->render('admin/appointments.html.twig', $data);

		})->name('appointments');

		// New appointment
		$app->post('/', function() use ($app){
			$params = $app->request->params();
			$appt = ORM::for_table('appts')->create();
			$appt->date = $params['date'];
			$appt->time = $params['time'];
			$appt->product = $params['product'];
			$appt->name = $params['name'];
			$appt->address = $params['address'];
			$appt->city = $params['city'];
			$appt->state = $params['state'];
			$appt->zip = $params['zip'];
			$appt->phone = $params['phone'];
			$appt->email = $params['email'];
			$appt->save();

			$app->redirect('appointments');
		});

		$app->put('/', function() use ($app) {
			$date = new DateTime();
			$testimonial = ORM::for_table('appts')->find_one($app->request->params('appt-id'));
			$testimonial->completed_at = $date->format('Y-m-d H:i:s');
			$testimonial->save();

			$app->flash( 'success', 'Appointment Completed' );
			$app->redirect( './appointments' );
		});

		$app->delete('/', function() use ($app) {
			$date = new DateTime();
			$testimonial = ORM::for_table('appts')->find_one($app->request->params('appt-id'));
			$testimonial->delete();

			$app->flash( 'fail', 'Appointment Deleted' );
			$app->redirect( './appointments' );
		});

	});

});




$app->run();