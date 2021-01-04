<?php

try{
    $db = new PDO('mysql:host=localhost;dbname=upload', 'root', "");
} catch(PDOException $e){
    die('Erreur: '.$e->getMessage());
}

if(!empty($_FILES)){
    $file_name = $_FILES['fichier']['name'];
    $file_extension = strrchr($file_name,".");

    $file_tmp_name = $_FILES['fichier']['tmp_name'];
    $file_dest = 'files/'.$file_name;

    $extension_autorise= array('.jpg', '.jpeg', '.png', '.gif');

    if(in_array($file_extension, $extension_autorise)){
        if(move_uploaded_file($file_tmp_name, $file_dest)){
            $req = $db->prepare('INSERT INTO files(name, file_url) values (?,?)');
            $req->execute(array($file_name, $file_dest));
            echo 'Image envoyée';
        } else {
            echo 'Une erreur est survenue';
        }
    } else {
        echo 'Seuls les images sont acceptées';
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <title>Upload de fichier</title>
    <meta charset="UTF-8" />
</head>
<body>
    <h1> Uploader un fichier</h1>
    <form method="POST" enctype="multipart/form-data">
    <input type="file" name="fichier"/> <br>
    <input type="submit" value="Envoyer le fichier" />
    </form>
    <h1>Images enregistrés</h1>
    <?php
    $req = $db->query('select name, file_url from files');
    while ($data = $req->fetch()){
        echo $data['name'].' : '.'<a href="'.$data['file_url'].'">Télécharger '.$data['name'].'</a><br>';
    }
    ?>
</body>
</html>
<?php
