<?php
    use Illuminate\Support\Facades\DB;
   
    $__RUN__ = 1;
	// ini_set('display_errors', 1);


    // DEFAULT
        // CONFIG
            $_DIR = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

            if(isset($_SERVER['REQUEST_URI'])){
                $_SERVER['REQUEST_URI'] = str_replace('/index.php', '', $_SERVER['REQUEST_URI']);
                $_DIR_G = explode($_DIR.'/', trim($_SERVER['REQUEST_URI']));
                $_DIR_URL = explode('/', isset($_DIR_G[1]) ? $_DIR_G[1] : '');
            } else {
                $_DIR_URL[0] = 'less';
            }
            function pre__e($array){ echo '<pre>'; print_r($array); echo '</pre>'; }
            // pre__e($_DIR_URL);
        // CONFIG
    // DEFAULT




















    // LESS
        if($_DIR_URL[0] == 'less'){
            require_once __DIR__.'/php/less.php';
        }
    // LESS




















    // LIGHTHOUSE / GTMETRIX
        elseif(isset($_SERVER['HTTP_USER_AGENT']) AND (isset($_GET['metrix__']) OR preg_match('(Lighthouse)', $_SERVER['HTTP_USER_AGENT']) OR preg_match('(GTmetrix)', $_SERVER['HTTP_USER_AGENT'])) ){
            require_once __DIR__.'/php/lighthouse__gtmetrix.php';
        }
    // LIGHTHOUSE / GTMETRIX




















    // SITE (DIST)
        else {
            // DEFAULT
                // HTTPS
                    if((!isset($_SERVER['HTTPS']) OR $_SERVER['HTTPS']!='on') AND $_SERVER['HTTP_HOST']!='localhost:4000'){
                        echo '<script> window.parent.location = "https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'"; </script>';
                        exit();
                    }
                // HTTPS
            // DEFAULT










            // .DIST
                else {
                    // META
                        $__META__  = '';
                        if($_DIR_URL[0] != 'admin'){
                            require_once __DIR__.'/php/meta.php';
                        }
                    // META

                    // SCRIPT
                        if($_DIR_URL[0] != 'admin'){
                            $x_settings = DB::table('x_settings')->where('fields', 'script')->get();
                        }
                    // SCRIPT


					if(file_exists(__DIR__.'/.dist/index.html')){
	                    ob_start();
	                        require_once __DIR__.'/.dist/index.html';
	                        $html = ob_get_contents();
	                    ob_end_clean();
	
	                    $html = str_replace('<meta/>', $__META__, $html);
                        $html = str_replace('<script>//SCRIPT</script>', $x_settings[0]->value??'', $html);

                        echo $html;

					} else {
						echo 'NO ->'.__DIR__.'/.dist/index.html';
					}
                }
            // .DIST

        }
    // SITE (DIST)
    
?>