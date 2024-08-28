<?php
require_once('../../Controllers/conexion.php');

if (isset($_GET['docid'])) 
{
    #Se va a mostrar desde la BD el archivo.
    $consultaDoc = $con->query("SELECT Archivo, Nombre_Archivo FROM tb_web_va_docs WHERE ID_Documento = " . $_GET['docid']);

    // var_dump($consultaDoc->fetch_assoc());
    // echo "Consulta de imagen";
    $datosArchivo = $consultaDoc->fetch_assoc();
    // var_dump($datosArchivo);
    $documento = $datosArchivo['Archivo'];
    $nombre_archivo = $datosArchivo['Nombre_Archivo'];
    // print_r(base64_decode($documento));
    $nomDocArch = explode(".", $nombre_archivo);
    $tipoDocArch = $nomDocArch[count($nomDocArch) - 1];
    //echo "Tipo de documento " . $tipoDocArch;
    // $imageSize = strlen($documento);
    // echo "Tamaño de la imagen: " . $imageSize . " bytes";
}

// ob_clean(); // Limpiar el búfer de salida
// flush();    // Limpiar el búfer de salida
if($tipoDocArch == "pdf")
{
    header("Content-Type: application/$tipoDocArch");
    echo base64_decode($documento);
}
else if($tipoDocArch == "png")
{
    // echo "Imagen Png";
    //header("Content-Type: image/x-png");

    echo '<img src="data:image/png;base64,'.$documento.'" class="object-fit-xl-contain border rounded"><img>'; //LINEA BUENA

    // echo '<embed src="data:image/png;base64,'.$documento.'" width="100%" height="600px" type="image/png"/>';
    // echo "data:image/png;base64,'$documento'";
    // echo imagecreatefromstring($documento);
}
else if($tipoDocArch == "jpg" || $tipoDocArch == "jpeg")
{
    // header("Content-Type: image/jpeg");
    echo '<img src="data:image/jpeg;base64,'.$documento.'" class="object-fit-xl-contain border rounded"><img>';
}
// header("Content-disposition: inline; filename='$nombre_archivo'");
// header("Content-Transfer-Encoding: binary");
// header("Accept-Ranges: bytes");

// echo $documento;
// echo base64_encode($documento);
