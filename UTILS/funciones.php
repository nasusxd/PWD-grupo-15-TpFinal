<?php 
function datasubmitted() {
    $_AAux= array();
    if (!empty($_POST))
        $_AAux =$_POST;
        else
            if(!empty($_GET)) {
                $_AAux =$_GET;
            }
        if (count($_AAux)){
            foreach ($_AAux as $indice => $valor) {
                if ($valor=="")
                    $_AAux[$indice] = 'null' ;
            }
        }
        return $_AAux;
        
}

// Autoload automático de clases
spl_autoload_register(function ($clase) {
    // Definimos los directorios donde buscar clases
    $directorios = [
        $GLOBALS['ROOT'] . 'CONTROL/',
        $GLOBALS['ROOT'] . 'MODELO/',
        $GLOBALS['ROOT'] . 'MODELO/conector/',
        $GLOBALS['ROOT'] . 'UTILS/',
    ];

    // Recorremos los directorios buscando el archivo que coincida con el nombre de la clase
    foreach ($directorios as $dir) {
        $archivo = $dir . $clase . '.php';
        if (file_exists($archivo)) {
            require_once($archivo);
            return;
        }
    }
});

?>