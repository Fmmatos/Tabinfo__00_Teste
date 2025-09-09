<?php

    if(isset($__RUN__)){
        ini_set('display_errors', 1);
		date_default_timezone_set("America/Sao_Paulo");


        // FUNCTIONS
            function pre($array){ echo '<pre>'; print_r($array); echo '</pre>'; }

            $DIR = __DIR__.'/..';

            $DIR_APP = $DIR.'/../..app/dist';
            $DIR_PUBLIC_HTML = $DIR.'/.dist';

            // COPIAR ARQUIVOS
                function copy_files($souce, $destination, $_DIR){
                    if (is_dir($souce)) {                    
                        // CRIANDO PASTA
                            if (!is_dir($destination)) {
                                if (!mkdir($destination, 0755, true)) {
                                    echo '<div>Pasta '.$destination.' não foi criada!</div>';
                                }
                            }
                        // CRIANDO PASTA

                        // APAGAR ARQUIVOS PASTA .DIST ATUAL
                            foreach (glob("{$destination}/*") as $file) {
                                if (is_file($file)) {
                                    unlink($file);
                                }
                            }
                        // APAGAR ARQUIVOS PASTA .DIST ATUAL

                        // LENDO DIRETORIO
                            $http = $_SERVER['HTTP_HOST']=='localhost:4000' ? 'http' : 'https';

                            $files = scandir($souce);
                            $files = array_diff($files, array('.', '..'));
                            foreach ($files as $file) {
                                $path = $souce.'/'.$file;

                                // FILE      
                                    if (is_file($path)) {
                                        if(preg_match('(app.)', $file) AND preg_match('(.js)', $file)){
                                            $content = file_get_contents($souce.'/'.$file);
                                            $newContent = str_replace('__Z__DIR__Z__', $http."://".$_SERVER['HTTP_HOST'].$_DIR.'/.dist', $content);
                                            if(!file_put_contents($destination.'/'.$file, $newContent)){
                                                echo '<div>'.$file.' não foi copiado!!</div>';
                                            }

                                        } elseif($file == 'index.html') {
                                            $content = file_get_contents($souce.'/'.$file);
                                            $newContent = str_replace('__Z__DIR__Z__', $_DIR.'/.dist', $content);

                                            // DIR
                                                $newContent = str_replace("DIR: ''", "DIR: '".$_DIR."'", $newContent);
                                            // DIR

                                            // DIR_API
                                                $ex_1 = explode("DIR_API: '", $newContent);
                                                $ex_2 = explode("/api',", $ex_1[1]);
                                                $newContent = $ex_1[0]."DIR_API: '".$http."://".$_SERVER['HTTP_HOST'].$_DIR."/api',".$ex_2[1];
                                            // DIR_API

                                            // CSS
                                                $newContent = str_replace('href="/css/', 'href="'.$_DIR.'/.dist/css/', $newContent);
                                                $newContent = str_replace('src:url("/css/', 'src:url("'.$_DIR.'/.dist/css/', $newContent);
                                            // CSS

                                            // JS_ROOT
                                                $newContent = str_replace('${$_GLOBAL.DIR_API}/../../js_root.js', $_DIR.'/js_root.js', $newContent);
                                            // JS_ROOT

                                            // JS_ADMIN
                                                $newContent = str_replace('${$_GLOBAL.DIR_API}/../../js_admin.js', $_DIR.'/js_admin.js', $newContent);
                                            // JS_ADMIN
                                            
                                            if(!file_put_contents($destination.'/'.$file, $newContent)){
                                                echo '<div>'.$file.' não foi copiado!!</div>';
                                            }

                                        } else {
                                            if(!copy($path, $destination.'/'.$file)){
                                                echo '<div>'.$file.' não foi copiado!</div>';
                                            }
                                        }
                                    }
                                // FILE

                                // PASTA
                                    elseif (is_dir($path)) {
                                        if (!is_dir($destination.'/'.$file)) {
                                            if (!mkdir($destination.'/'.$file, 0777, true)) {
                                                echo '<div>Pasta '.$path.' não foi criada!!</div>';
                                            }
                                        }
            
                                        $souce_new = $souce.'/'.$file;
                                        $destination_new = $destination.'/'.$file;
                                        copy_files($souce_new, $destination_new, $_DIR);
                                    }
                                // PASTA
                            }
                        // LENDO DIRETORIO
                    }
                }
            // COPIAR ARQUIVOS





            // ZIP
				if( !(stripos($_SERVER['HTTP_HOST'], 'localhost:') !== false) ){
					
				    $file_zip = '../..app/up.zip';
				    $destination = '../..app/';
    
				    $zip = new ZipArchive;
				    if ($zip->open($file_zip) === TRUE) {

						// BACK-UP
			                $dir_old  = '../..app/';
			                $dir_new = '../z_old/..app__'.date('Y_m_d__H_i_s').'/';
		
							mkdir($dir_new, 0777, true);

							// _root
								$dir__ = '_root/';
								if(is_dir($dir_old.$dir__)){
				                	rename($dir_old.$dir__, $dir_new.$dir__);
								}
							// _root

							// _vendor
								$dir__ = '_vendor/';
								if(is_dir($dir_old.$dir__)){
				                	rename($dir_old.$dir__, $dir_new.$dir__);
								}
							// _vendor

                            // dist
								$dir__ = 'dist/';
								if(is_dir($dir_old.$dir__)){
				                	rename($dir_old.$dir__, $dir_new.$dir__);
								}
							// dist
						// BACK-UP


						// ZIP
					        $zip->extractTo($destination);
					        $zip->close();

							if(file_exists($file_zip)){
								unlink($file_zip);
							}							

					        echo '<div style="padding: 20px">ZIP!</div>';
						// ZIP

				    } else {
				    	echo '<div style="padding: 20px">Erro -> ZIP!</div>';
				    }


					// LARAVEL
					    $file_zip = '../..app/laravel.zip';
					    $destination = '../..app/';
	    
					    $zip = new ZipArchive;
					    if ($zip->open($file_zip) === TRUE) {
					        $zip->extractTo($destination);
					        $zip->close();
	
							if(file_exists($file_zip)){
								unlink($file_zip);
							}

					        echo '<div style="padding: 20px">ZIP - LARAVEL!</div>';
						}
					// LARAVEL


					// DATA
					    $file_zip = '../..data.zip';
					    $destination = '../';
	    
					    $zip = new ZipArchive;
					    if ($zip->open($file_zip) === TRUE) {
					        $zip->extractTo($destination);
					        $zip->close();
	
							if(file_exists($file_zip)){
								unlink($file_zip);
							}

					        echo '<div style="padding: 20px">ZIP - DATA!</div>';
						}
					// DATA


					// PUBLIC_HTML
					    $file_zip = '../..public_html.zip';
					    $destination = '../';
	    
					    $zip = new ZipArchive;
					    if ($zip->open($file_zip) === TRUE) {
					        $zip->extractTo($destination);
					        $zip->close();
	
							if(file_exists($file_zip)){
								unlink($file_zip);
							}

					        echo '<div style="padding: 20px">ZIP - PUBLIC_HTML!</div>';
						}
					// PUBLIC_HTML
				}
            // ZIP
        // FUNCTIONS


        // EVENTOS
            // CRIANDO PASTA .DIST
                if (!is_dir($DIR_PUBLIC_HTML)) {
                    if (!mkdir($DIR_PUBLIC_HTML, 0777, true)) {
                        echo '<div>Pasta .dist não foi criada!</div>';
                    }
                }
            // CRIANDO PASTA .DIST

            copy_files($DIR_APP, $DIR_PUBLIC_HTML, $_DIR);
        // EVENTOS


        echo '<div style="padding: 20px">'.date('Y-m-d H:i:s').'</div>';
        echo '<div style="padding: 20px"><a href="'.$_DIR.'/">Home</a></div>';
        echo '<div style="padding: 20px"><a href="'.$_DIR.'/admin/">Admin</a></div>';

    }

?>