<!DOCTYPE html>
<html>
<head>
<style type="text/css">@import "style.css";</style>
</head>
<body>

<?php

require_once ('libreria.php'); // carica libreria


if (isset($_POST['SubmitButton'])) {  //verifica se si arriva dopo aver cliccato il tasto "Calcola"
    $input1 = $_POST['fDividendo'];   //get input text
    $input2 = $_POST['lDivisore'];    //get input text
    $input3 = $_POST['fMostraProd'];  //get input text
}
else
{
    // valori di default
    $input1 = 1345;
    $input2 = 2.1;
    $input3 = "on";
}

?>

Inserisci i fattori per il calcolo della divisione, e clicca il tasto "Calcola"

<!-- form asking for factors -->
<form action="" method="post">
    <br>
    <label for="fDividendo">Dividendo:</label><br>
    <input type="text" id="fDividendo" name="fDividendo" value="<?=$input1?>"><br>
    <label for="lDivisore">Divisore:</label><br>
    <input type="text" id="lDivisore" name="lDivisore" value="<?=$input2?>"><br>
    <br>
    <input type="checkbox" id="fMostraProd" name="fMostraProd" <?=is_null($input3)?"":"checked";?>>
    <label for="fMostraProd">Mostra la riga del prodotto</label><br>

    <input type="submit" value="Calcola" name="SubmitButton">
</form>
<br>

<!-- show product result -->
<hr>
<br>

<?php

if (isset($_POST['SubmitButton'])) { //check if form was submitted
  $input1 = $_POST['fDividendo'];   //get input text
  $input2 = $_POST['lDivisore'];    //get input text
  $input3 = $_POST['fMostraProd'];  //get input text
  $n1 = floatval($input1);
  $n2 = floatval($input2);
  $flg_show_prodotto = ($input3 == "on");
  // echo("Input are: |$input1:$input2-$input3|");
  divisione($n1,$n2,$flg_show_prodotto);
} else {
    divisione();
}



function divisione($n1 = 1345, $n2 = 2.1, $flg_show_prodotto = true) {

$result = forma_canonica_divisione($n1,$n2);
$k1       = $result['k1'];
$k2       = $result['k2'];
$num_dec1 = $result['num_dec1'];
$num_dec2 = $result['num_dec2'];
$num_1st  = $result['num_1st'];

$debug = 0;

echo_my("n1:$n1 - n2:$n2 - k1: $k1 - k2:$k2 - num_dec1:$num_dec1 - num_dec2:$num_dec2 - num_1st:$num_1st<br>",$debug);

if ($n2 == 0) {
    echo("Attenzione! Il divisore non può essere 0!<br>");
}
else
{
    // esegui la divisione

    // var_dump($result);
    $page = Array();

    // $page = scrivi($page,1,1,"*");
    // $page = scrivi($page,-3,1,"*234567");
    // $page = scrivi($page,1,-50," ");

    $page = scrivi($page,-1,1-strlen($k1),"$k1 :");

    // $page = scrivi($page,-1,4,"$k2 ="); // mostra anche l'uguale
    $page = scrivi($page,-1,4,$k2);

    $num_cifre_out = strlen($k1)-$num_1st+1; // numero cifre quoziente
    // $page = scrivi($page,0,-strlen($k1)+1,str_repeat('-',strlen($k1)+$num_cifre_out+3)); // la linea passa anche sotto al dividendo
    $page = scrivi($page,0,2,str_repeat('-',$num_cifre_out+3)); // linea solo sotto il divisore


    if ($flg_show_prodotto) {
        $off_prodotto = 1; // il resto andrà una riga sotto, visto che si visualizza anche il prodotto
    }
    else
    {
        $off_prodotto = 0; // non visualizzando il resto, non serve scendere di una riga sotto
    }

    $num_abbasso = $num_1st; // numero cifre da abbassare
    $pos_abbasso = 0; // posizione cifre da abbassare in $k1 (0-based)
    $accum = ""; // accumulatore cifre abbassate
    $ks_resto = "";
    $quoz = ""; // quoziente

    // per ogni volta che si abbassa, itera:
    for ($i_level=1;$i_level<=$num_cifre_out;$i_level++) {

        $accum = $ks_resto.substr($k1,$pos_abbasso,$num_abbasso); // prime cifre abbassate
        $riga_base = ($i_level-1)*(1+$off_prodotto)+1;

        $col_abbasso = -strlen($k1)+$pos_abbasso+$num_abbasso-1; // colonna ultima cifra "abbassata" (in $page)

        echo_my("iter $i_level/$num_cifre_out: accum:$accum - pos_abbasso:$pos_abbasso - num_abbasso:$num_abbasso - col_abbasso:$col_abbasso - riga_base: $riga_base<br>",$debug);

        $page = abbassa($page,-2,$col_abbasso-$num_abbasso+2,$pos_abbasso,$num_abbasso); // scrivi archetto in alto per indicare cifre abbassate
        $page = scrivi($page,$riga_base,$col_abbasso-strlen($accum)+2,$accum); // scrivi accumulatore (resto + ultima cifra abbassata)
        echo_my("--1> $riga_base,$col_abbasso-strlen($ks_resto),$accum<br>",$debug);

        // barra verticale
        $cifra = floor(intval($accum) / intval($k2));
        $page = scrivi($page,$riga_base,2,"|");
        $page = scrivi($page,$riga_base+1,2,"|");

        // nuova cifra della divisione
        $page = scrivi($page,1,3+$i_level,strval($cifra),$debug);
        $quoz .= $cifra;
        echo_my("$i_level) scrivo $cifra in riga 1, colonna 3+$i_level<br>",$debug);

        if ($flg_show_prodotto) {
            // mostra riga del prodotto, se richiesto
            $prod = $cifra*intval($k2);
            $page = scrivi($page,$riga_base+1,$col_abbasso+2-strlen($prod),strval($prod));
            $page = scrivi($page,$riga_base+2,2,"|"); // aggiungi anche una barretta verticale in più
        }

        // resto
        $resto = intval($accum) % intval($k2);
        $ks_resto = strval($resto);
        $ks_resto = str_repeat("0",max(0,$num_abbasso-strlen($ks_resto))).$ks_resto;
        $page = scrivi($page,$riga_base+(1+$off_prodotto),$col_abbasso+2-strlen($ks_resto),$ks_resto);

        $accum = $ks_resto;
        $pos_abbasso += $num_abbasso; // aggiorna posizione cifre da abbassare
        $num_abbasso = 1; // d'ora in avanti, una cifra alla volta
        $riga_base += 1+$off_prodotto;

        echo_my("cifra:$cifra - resto:$resto<br>",$debug);
        if ($debug) {
            stampa($page);
        }
    }

    echo("<table>\n\t<tr>\n\t\t<td style=\"width:30%\"></td><td>\n\n");
    stampa($page);
    echo("\n</td></tr></table>\n\n");

    // echo('<br>');

    $num_dec_out = max(0,$num_dec1-$num_dec2);
    $num_dec_resto = max(0,$num_dec1);

    $fmt1    = sprintf("%%.%df",$num_dec1);
    $fmt2    = sprintf("%%.%df",$num_dec2);
    $fmt_out = sprintf("%%.%df",$num_dec_out);
    $fmt_resto = sprintf("%%.%df",$num_dec_resto);

    $ks1    = sprintf($fmt1,$n1);
    $ks2    = sprintf($fmt2,$n2);
    echo_my("fmt_out:$fmt_out - k1: $k1 - k2:$k2 - ks1: $ks1 - ks2:$ks2 - num_dec1:$num_dec1 - num_dec2:$num_dec2 - num_1st:$num_1st - num_dec_resto:$num_dec_resto<br>",$debug);
    $ks_out__ = sprintf($fmt_out,intval($quoz)/pow(10,$num_dec1-$num_dec2));
    $ks_resto__ = sprintf($fmt_resto,intval($resto)/pow(10,$num_dec_resto));

    echo("<br>\n");
    if ( ($num_dec1>0) || ($num_dec2>0) ) {
        echo("<!-- info on decimal digits -->\n");
        echo("Il dividendo $ks1 ha ".strval($num_dec1)." ".text_cifre_decimali($num_dec1)."<br>\n");
        echo("Il divisore $ks2 ha ".strval($num_dec2)." ".text_cifre_decimali($num_dec2)."<br>\n");
        echo("Il risultato avrà ".max(0,strval($num_dec1-$num_dec2))." ".text_cifre_decimali($num_dec1-$num_dec2)."<br>\n");
        echo("<br>\n");
    }

    echo("<!-- final result -->\n");
    printf("$ks1 : $ks2 = $ks_out__ (col resto di $ks_resto__)<br>\n");
}

echo("<!-- go back -->\n");
echo("<br><br>");
echo("<a href=\"operazioni.php\">Torna indietro</a>");

} // end function divisione



///////////////////////////////////////////////////////////////////////
function abbassa($page,$row,$col,$pos_abbasso,$num_abbasso) {
switch ($num_abbasso) {
    case 1:
        $ks = "^";
        break;
    case 2:
        $ks = "/\\";
        break;
    default:
        $ks = "/".str_repeat("-",$num_abbasso-2)."\\";
}
$page = scrivi($page,$row,$col,$ks);

return $page;

} // end function abbassa



///////////////////////////////////////////////////////////////////////
function forma_canonica_divisione($n1,$n2) {

$k1 = strval($n1);
$k2 = strval($n2);

$pos_dot = strpos($k1,'.');
if ($pos_dot === false) {
    $num_dec1 = 0;
} else {
    $num_dec1 = strlen($k1)-strpos($k1,'.')-1;
    // echo("$k1 --> |$num_dec1|<br>");
}

$pos_dot = strpos($k2,'.');
if ($pos_dot === false) {
    $num_dec2 = 0;
} else {
    $num_dec2 = strlen($k2)-strpos($k2,'.')-1;
    // echo("$k2 --> |$num_dec2|<br>");
}

if ( $num_dec1>0 ) {
    // intero
    $k1 = strval($n1*pow(10,$num_dec1));
}

if ( $num_dec2>0 ) {
    // intero
    $k2 = strval($n2*pow(10,$num_dec2));
}

// echo("$n1 -> $k1,$num_dec1 - $n2 -> $k2,$num_dec2<br>");


// calcolo numero di cifre da alzare al primo passo
$num_1st = strlen($k2);
$ks0 = substr($k1,0,$num_1st);
$tot_cifre1 = strlen($k1);
echo_my("alzo $num_1st cifre su $tot_cifre1: $ks0<br>",$debug);
while ( (intval($ks0) < intval($k2)) & ($num_1st < $tot_cifre1) ) {
    $num_1st++;
    $ks0 = substr($k1,0,$num_1st);
    echo_my("alzo $num_1st cifre su $tot_cifre1: $ks0<br>",$debug);
}
if ($num_1st > $tot_cifre1) {
    die("Errore: il dividendo deve essere maggiore del divisore (1)!<br>");
}
if (intval($ks0) < intval($k2)) {
    die("Errore: il dividendo deve essere maggiore del divisore (2)!<br>");
}
echo_my("Ok, alzo $ks0 ($num_1st)<br>",$debug);

$result = Array(
    "k1"        =>  $k1,
    "k2"        =>  $k2,
    "num_dec1"  =>  $num_dec1,
    "num_dec2"  =>  $num_dec2,
    "num_1st"   =>  $num_1st,
);
// var_dump($result);

return $result;
} // end function forma_canonica_divisione


?>


</body>
</html>
