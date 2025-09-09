<?	ob_start(); if(!isset($no_session_start)){ session_start(); }
        
    if(isset($_SESSION['download'])){

        $file = base64_decode(str_replace(';;zz;;', '/', $_GET['file']));
        $name = $_GET['name']? base64_decode(str_replace(';;zz;;', '/', $_GET['name'])) : 'arquivo';

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $name = $name.'.'.$ext;

        $content = file_get_contents($file);
        if ($content !== false AND stripos($file, 'web/photos/') !== false){
            header("Content-Disposition: attachment; filename=".$name);
            header("Content-Type: application/octet-stream");
            header("Content-Length: ".strlen($content));
            echo $content;
            unset($_SESSION['download']);
            exit();

        } else {
            echo "Não foi possível acessar o arquivo.";
        }

    } else {
        echo "Você não pode acessar esse arquivo.";
    }