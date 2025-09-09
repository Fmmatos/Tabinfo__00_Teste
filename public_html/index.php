<?php
	// echo '<pre>'; print_r($_SERVER); echo '</pre>';


	// MIMETYPES
		$MimeTypes = [
			// Text & Code Files
			'css'   => 'text/css',
			'js'    => 'text/javascript',
			'txt'   => 'text/plain',

			// Font Files
			'eot'   => 'application/vnd.ms-fontobject',
			'woff2' => 'font/woff2',
			'woff'  => 'font/woff',
			'ttf'   => 'font/ttf',

			// Images
			'jpg'   => 'image/jpeg',
			'jpeg'  => 'image/jpeg',
			'png'   => 'image/png',
			'webp'  => 'image/webp',
			'gif'   => 'image/gif',
			'svg'   => 'image/svg+xml',
			'ico'   => 'image/x-icon',
		];

		$REQUEST_URI = explode('?', $_SERVER['REQUEST_URI']);
		$REQUEST_URI = $REQUEST_URI[0];
		$fileExtension = strtolower(pathinfo($REQUEST_URI, PATHINFO_EXTENSION));

		if(isset($MimeTypes[$fileExtension])){
			$DIR = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
			$FILE = str_replace($DIR, '', $REQUEST_URI);

			header('Content-Type: '.$MimeTypes[$fileExtension]);
			readfile(__DIR__.'/../..public_html'.$FILE);
			exit;
		}
	// MIMETYPES










	// API
		if(stripos($_SERVER['REQUEST_URI'], '/api/') !== false){

			// INICIA O TEMPO DE EXECUÇÃO DO LARAVEL
				define('LARAVEL_START', microtime(true));
			// INICIA O TEMPO DE EXECUÇÃO DO LARAVEL

			// Determine if the application is in maintenance mode...
			if (file_exists($maintenance = __DIR__.'/../..app/laravel/storage/framework/maintenance.php')) {
				require $maintenance;
			}

			// REGISTER THE COMPOSER AUTOLOADER
				require __DIR__.'/../..app/laravel/vendor/autoload.php';
			// REGISTER THE COMPOSER AUTOLOADER

			// BOOTSTRAP LARAVEL AND HANDLE THE REQUEST
				/** @var Application $app */
				$app = require_once __DIR__.'/../..app/laravel/bootstrap/app.php';
			// BOOTSTRAP LARAVEL AND HANDLE THE REQUEST

			// HANDLE THE REQUEST
				$app->handleRequest(\Illuminate\Http\Request::capture());
			// HANDLE THE REQUEST
			
			exit();
		}
	// API










	// QRCODE
		if(stripos($_SERVER['REQUEST_URI'], '/qrcode?txt=') !== false){
			// ?d='.$_GET['txt'].'&e=H&s=10&t=J
			require_once(__DIR__.'/../..public_html/php/qrcode/php/qr_img.php');


			exit();
		}
	// QRCODE










	// ELSE
		ob_start();
			require_once __DIR__.'/../..public_html/index.php';
			$return = ob_get_contents();
		ob_end_clean();
		echo $return;
	// ELSE

?>