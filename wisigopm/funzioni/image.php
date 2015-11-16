<?php


try
{
    $old_path='10008000017-3.jpg';
    $img = new Imagick($old_path);
    $img->thumbnailImage(500 , 0);
    $new_path="immagini/10008000017.jpg";
    $img->setOption('jpeg:extent', '100kb');
    $img->writeImage($new_path);

}
catch(Exception $e)
{
    echo 'Caught exception: ',  $e->getMessage(), "n";
    $error++;
}

?>