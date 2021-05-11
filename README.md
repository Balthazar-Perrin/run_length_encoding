# Groupe de chou_c

Pour lancer notre programme, il faut lancer le main.php situé à la racine du repo.
Ci-dessous quelques exemples de commandes que vous pouvez utiliser pour tester notre rle :

echo encode_rle("AAABBBBCC");  
echo encode_rle("AAABB5BBCC"); => erreur

echo decode_rle("3A4B2C");  
echo decode_rle("3A'4B2C"); => erreur

echo encode_advanced_rle("./src/iggy.bmp", "./bmp2.oui");           => image complexe en teintes de gris  
echo encode_advanced_rle("./src/mondrian.bmp", "/mondrian2.bmp");   => image en couleur  
echo encode_advanced_rle("./src/image.bmp", "/image2.bmp");         => image simple en noir et blanc  

echo encode_advanced_rle("./src/iggy.bmp", "./iggy.bmp");           => si le chemin de destination existe déjà, le rle créé un nouveau fichier


echo decode_advanced_rle("/src/encoded", "/src/toto.bmp");
echo decode_advanced_rle("/src/encoded_false", "/src/toto.bmp");  => si le contenu ne correspond pas à un fichier encodé par un rle, erreur