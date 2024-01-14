<!DOCTYPE html>
<html>
<head>
<style type="text/css">@import "style.css";</style>
</head>
<body>

<?php

require_once ('libreria.php'); // load library


if (isset($_POST['SubmitButton'])) {    //check if form was submitted
  $input1 = $_POST['fMoltiplicando'];   //get input text
  $input2 = $_POST['lMoltiplicatore'];  //get input text
}

?>

Inserisci i fattori per il calcolo della moltiplicazione, e clicca il tasto "Calcola"

<!-- form asking for factors -->
<form action="" method="post">
    <br>
    <label for="fMoltiplicando">Moltiplicando:</label><br>
    <input type="text" id="fMoltiplicando" name="fMoltiplicando" value="<?=$input1?>"><br>
    <label for="lMoltiplicatore">Moltiplicatore:</label><br>
    <input type="text" id="lMoltiplicatore" name="lMoltiplicatore" value="<?=$input2?>">

    <input type="submit" value="Calcola" name="SubmitButton">
</form>
<br>

<!-- show product result -->
<hr>
<br>

<?php

if (isset($_POST['SubmitButton'])) { //check if form was submitted
  $input1 = $_POST['fMoltiplicando']; //get input text
  $input2 = $_POST['lMoltiplicatore']; //get input text
  $n1 = floatval($input1);
  $n2 = floatval($input2);
  // echo("Input are: $input1 x $input2");
  prodotto($n1,$n2);
} else {
    prodotto();
}



function prodotto($n1 = 23.2, $n2 = 98227) {

    // [k1 k2 num_dec1 num_dec2] = forma_canonica_prodotto(n1,n2);
    $result = forma_canonica_prodotto($n1,$n2);
    $k1       = $result['k1'];
    $k2       = $result['k2'];
    $num_dec1 = $result['num_dec1'];
    $num_dec2 = $result['num_dec2'];

    $page = Array();

    // echo('<br>');

    $page = scrivi($page,-2,1-strlen($k1),"$k1 x");

    // echo('<br>');
    // $page = scrivi($page,1,1,"*");

    // $page = scrivi($page,1,-50," ");

    $page = scrivi($page,-2,1-strlen($k1),"$k1 x");

    $page = scrivi($page,-1,1-strlen($k2),"$k2 =");

    $page = scrivi($page,0,-strlen($k1)-strlen($k2)+1,str_repeat('-',strlen($k1)+strlen($k2)+2));

$sub = Array();
for ($i_level=1;$i_level<=strlen($k2);$i_level++) {
    $c2 = substr($k2,-$i_level,1);
    $val2 = intval($c2);
    $riporto = 0;

    // echo("$i_level) val2: $val2<br>");

    for ($i_posiz=1; $i_posiz <= strlen($k1); $i_posiz++) {
        $c1 = substr($k1,-$i_posiz,1);
        $val1 = intval($c1);
        // echo("$i_level,$i_posiz) val2: $val2 - val1: $val1<br>");
        $prodot = $val1*$val2 + $riporto;
        $cifra = $prodot % 10;
        $riporto = intval(floor($prodot/10));

        $ind_sub = $i_posiz+$i_level-1;
        $sub[$ind_sub][] = $cifra;

        // echo("<br><br>cifre ($i_level,$i_posiz-->$val1 x $val2):<br>");
        // var_dump($sub);
        // echo("<br><br>");

        $page = scrivi($page,$i_level,2-$i_posiz-$i_level,intval($cifra));
        // echo("level ".intval($i_level).", posiz ".intval($i_posiz).": $c1 x $c2<br>");
    }

    if ($riporto > 0) {
        $ind_sub = $i_posiz+$i_level-1;
        $sub[$ind_sub][] = $riporto;

        // echo("<br><br>cifre ($i_level,$i_posiz-->$val1 x $val2):<br>");
        // var_dump($sub);
        // echo("<br><br>");

        // echo("riporto: $riporto in (".$i_level.",".(2-$i_posiz-$i_level).") - $i_level $i_posiz<br>");
        $page = scrivi($page,$i_level,2-$i_posiz-$i_level,intval($riporto));
    }

    for ($i_zero = 1; $i_zero <= $i_level-1; $i_zero++) {
        $page = scrivi($page,$i_level,1-$i_zero,'0');
    }
}

$r_result = strlen($k2)+2;
$page = scrivi($page,$r_result-1,1-strlen($k1)-strlen($k2),str_repeat('-',strlen($k1)+strlen($k2)+2));
// echo("<br><br>cifre:<br>");
// var_dump($sub);

$riporto = 0;
for ($i_sub = 1;$i_sub <= count($sub); $i_sub++ ) {
    $val = array_sum($sub[$i_sub])+$riporto;
    $cifra = $val % 10;
    $riporto = floor($val/10);
    $page = scrivi($page,$r_result,1-$i_sub,strval($cifra));
}

echo("<table>\n\t<tr>\n\t\t<td style=\"width:30%\"></td><td>\n\n");
stampa($page);
echo("\n</td></tr></table>\n\n");

// echo('<br>');


$fmt1    = sprintf("%%.%df",$num_dec1);
$fmt2    = sprintf("%%.%df",$num_dec2);
$fmt_out = sprintf("%%.%df",$num_dec1+$num_dec2);

$ks1    = sprintf($fmt1,$n1);
$ks2    = sprintf($fmt2,$n2);
$ks_out = sprintf($fmt_out,$n1*$n2);

echo("<br>\n");
if ( ($num_dec1>0) || ($num_dec2>0) ) {
    echo("<!-- info on decimal digits -->\n");
    echo("$ks1 ha ".strval($num_dec1)." ".text_cifre_decimali($num_dec1)."<br>\n");
    echo("$ks2 ha ".strval($num_dec2)." ".text_cifre_decimali($num_dec2)."<br>\n");
    echo("Il risultato avrà ".strval($num_dec1+$num_dec2)." ".text_cifre_decimali($num_dec1+$num_dec2)."<br>\n");
    echo("<br>\n");
}

echo("<!-- final result -->\n");
printf("$ks1 x $ks2 = $ks_out<br>\n",$n1,$n2,$n1*$n2);

echo("<!-- go back -->\n");
echo("<br><br>");
echo("<a href=\"operazioni.php\">Torna indietro</a>");

} // end function prodotto



///////////////////////////////////////////////////////////////////////
//function [k1 k2 num_dec1 num_dec2] = forma_canonica_prodotto(n1,n2)
function forma_canonica_prodotto($n1,$n2) {

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


$result = Array(
    "k1"        =>  $k1,
    "k2"        =>  $k2,
    "num_dec1"  =>  $num_dec1,
    "num_dec2"  =>  $num_dec2,
);
// var_dump($result);
return $result;

} // end function forma_canonica_prodotto


?>


</body>
</html>
