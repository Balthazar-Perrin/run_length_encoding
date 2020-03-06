<?php
function encode_rle($str) {

    // en cas d'erreur
    if (!ctype_alpha($str)) return "$$$";

    $char = $str[0];
    $cpt = 0;
    $result = "";

    for ($i = 0; $i <strlen($str); $i++) {
         if ($char != $str[$i]) {
         $result .= $cpt.$char;
         $char = $str[$i];
         $cpt = 0;
         }
    $cpt++;
    }
    $result .= $cpt.$char;
    return $result;
}

function decode_rle($str) {

        // en cas d'erreur
        if (!ctype_alnum($str)) return "$$$";

        $cpt = 0;
        $result = "";
        $char = '';

        for ($i = 0; $i < strlen($str); $i++) {
            while (ctype_digit($str[$i])) {
                  $cpt = $cpt * 10 + intval($str[$i], 10);
                  $i++;
            }
            for (; $cpt > 0; $cpt--) {
            // en cas d'erreur où 2 lettres se succèdent
            if ($i < strlen($str)-1)
                if (ctype_alpha($str[$i+1])) return "$$$";

            $result .= $str[$i];
        }
    }
    return $result;
}

function encode_advanced_rle(string $path_to_encode, string $result_path) {

    if (!file_exists($path_to_encode)) return "$$$";
    // on récupère le contenu de l'image en hexadécimal
    $image = file_get_contents($path_to_encode);
    $hexa = bin2hex($image);
    $cpt = 0;
    $cpthex = "";
    $pair = $hexa[0].$hexa[1];
    $ecrit = 0;
    $result = "";
    $pairsolo = "";
    $cptsolo = 0;
    $cptsolohex = "";

    for ($i = 0; $i <strlen($hexa); $i+=2) {
        if ($pair != $hexa[$i].$hexa[$i+1] || $cpt == 255) {

            if ($cpt > 1 || $cpt == 255) {$ecrit = 1;}

            if ($ecrit == 0) {
                $cptsolo++;
                $pairsolo .= $pair;
            }

            if ($cptsolo > 1 && $ecrit == 1) {
                $cptsolohex = dechex($cptsolo);
                if(strlen($cptsolohex) == 1) {$cptsolohex = "0".$cptsolohex;}  
                $result .= "00".$cptsolohex.$pairsolo;
                $pairsolo = "";
                $cptsolo = 0;
            }

            if ($cptsolo == 1 && $ecrit == 1) {
                $result .= "01".$pairsolo;
                $pairsolo = "";
                $cptsolo = 0;
            }

            if ($ecrit == 1) {
                $cpthex = dechex($cpt);
                if(strlen($cpthex) == 1) {$cpthex = "0".$cpthex;}
                $result .= $cpthex.$pair;
                $pairsolo = "";
                $cptsolo = 0;
            }
            $ecrit = 0;
            $pair = $hexa[$i].$hexa[$i+1];
            $cpt = 0;
        }
        $cpt++;
    }
    if ($cptsolo > 1) {
        $cptsolohex = dechex($cptsolo);
        if(strlen($cptsolohex) == 1) {$cptsolohex = "0".$cptsolohex;}  
        $result .= "00".$cptsolohex.$pairsolo;
    } else if ($cptsolo == 1) {
        $result .= "01".$pairsolo;
    }
    if ($cpt > 1) {
        while ($cpt > 255) {
            $cpthex = dechex($cpt);
            $result .= $cpthex.$pair;
            $cpt -= 255;
        }
        $cpthex = dechex($cpt);
        if(strlen($cpthex) == 1) {$cpthex = "0".$cpthex;}
        $result .= $cpthex.$pair;
    }
    $result.="0000";

    if (file_exists($result_path)){
        while(file_exists($result_path)){
            $fait = 0;
            $new_result = "";
            for ($k = 0;$k < strlen($result_path); $k++){
                $new_result .= $result_path[$k];
                if($k < strlen($result_path)-1 && $result_path[$k+1] == "."){
                    $new_result .= "(1)";
                    $fait = 1;
                }else if ($k == strlen($result_path)-1 && $fait != 1){
                    $new_result .= "(1)";
                }
            }
            $result_path = $new_result;
        }
    }
        $fichier = fopen($result_path, 'wb');
        fwrite($fichier, $result);
        fclose($fichier);
    
    
    
    return $result_path;
}

function decode_advanced_rle(string $path_to_encode, string $result_path) {

    if (!file_exists($path_to_encode)) return "$$$";
    // on récupère le contenu de l'image en hexadécimal
    $str = file_get_contents($path_to_encode);

    if (!ctype_xdigit($str)) return "$$$";

    $chaine = "";
    $pair = $str[0].$str[1];
    $count = 0;
    $nbr = "";

    for ($i = 0; $i < strlen($str); $i+=4){
        $pair = $str[$i].$str[$i+1];
        if ($pair == "00"){
            if ($str[$i+2].$str[$i+3] != "00"){
                $count = hexdec($str[$i+2].$str[$i+3]);
                for ($i+=4; $count != 0; $count--){
                    $chaine .= $str[$i].$str[$i+1];
                    $i+=2;
                }
            }
        }
        $nbr = hexdec($str[$i].$str[$i+1]);
        for (; $nbr != 0; $nbr--){
            $chaine .= $str[$i+2].$str[$i+3];
        }
        $nbr = 0;
    }

    if (file_exists($result_path)){
        while(file_exists($result_path)){
            $fait = 0;
            $new_result = "";
            for ($k = 0;$k < strlen($result_path); $k++){
                $new_result .= $result_path[$k];
                if($k < strlen($result_path)-1 && $result_path[$k+1] == "."){
                    $new_result .= "(1)";
                    $fait = 1;
                }else if ($k == strlen($result_path)-1 && $fait != 1){
                    $new_result .= "(1)";
                }
            }
            $result_path = $new_result;
        }
    }
        $fichier = fopen($result_path, 'wb');
        fwrite($fichier, hex2bin($chaine));
        fclose($fichier);
    
    
    return $result_path;
}

?>