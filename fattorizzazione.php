<!DOCTYPE html>
<html>
<head>
<style type="text/css">@import "style.css";</style>
</head>
<body>

<?php

require_once ('libreria.php'); // load library


if (isset($_POST['SubmitButton'])) {    //check if form was submitted
  $input1 = $_POST['fNumero'];   //get input text
}
else
{
    // valori di default
    $input1 = 45;
}

?>

Inserisci il numero intero da scomporre in fattori, e clicca il tasto "Calcola"

<!-- form asking for factors -->
<form action="" method="post">
    <br>
    <label for="fNumero">Numero da scomporre:</label><br>
    <input type="text" id="fNumero" name="fNumero" value="<?=$input1?>"><br>

    <input type="submit" value="Calcola" name="SubmitButton">
</form>
<br>

<!-- show product result -->
<hr>
<br>

<?php

if (isset($_POST['SubmitButton'])) { //check if form was submitted
    $input1 = $_POST['fNumero']; //get input text
    $n1 = floatval($input1);
    // echo("Input are: $input1");
    divisori($n1);
} else {
    divisori();
}



function divisori($n1 = 45) {

    if (fmod($n1,1) != 0) {
        die("Attenzione! Il numero da scomporre deve essere intero!");
    } else {
        // gestisci come intero
        $n1 = intval($n1);
    }

    $k1 = strval($n1);

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $page = Array();

    // $page = scrivi($page,1,1,"*");

    $page = scrivi($page,1,-1-strlen($k1),"$k1 = ");

    // scomponi nei fattori primi
    $list = Array();
    $accum = $n1;
    $col = 2;
    for ($iter = 2; $iter<=$n1; $iter++) {
        if ($accum % $iter == 0) {
            // cerca l'esponente del fattore primo $iter
            $esp = 0;
            do {
                $esp = $esp+1;
                $accum = $accum/$iter;
            } while ($accum % $iter == 0);
            $list[$iter] = $esp;
            // echo("$n1 $accum $iter $esp<br>");

            $ks = "$iter";
            if ($esp > 1) {
                // mostra l'esponente
                $ks .= "^$esp ";
            } else {
                // implicit exponent is 1, non need to show it
                $ks .= " ";
            }
            if ($accum > 1) {
                $ks .= "x ";
            }
            $page = scrivi($page,1,$col,$ks);
            $col = $col+strlen($ks);
            // echo("$iter<br>");
            if ($accum == 1) {
                // tutti i fattori sono stati individuati, esci dal ciclo for
                break;
                }
            }
    }

    // stampa($page);die("aaa");
    // mostra tutti i possibili divisori
    $max_num = 1e9; // l'analisi verrà effettuata fino a questo numero, per motivi di durata dei calcoli
    $level = 0;
    for ($iter = 1; $iter<=min($max_num,$n1); $iter++) {
        if ($n1 % $iter == 0) {
            $level++;
            $page = scrivi($page,2+$level,2,"$iter");
            // echo("$iter<br>");
        }
    }
    if ($n1 > $max_num) {
        $level++;
        $page = scrivi($page,2+$level,2,"...");
    }

echo("<table>\n\t<tr>\n\t\t<td style=\"width:30%\"></td><td>\n\n");
stampa($page);
echo("\n</td></tr></table>\n\n");

// echo('<br>');

echo("<!-- go back -->\n");
echo("<br><br>");
echo("<a href=\"operazioni.php\">Torna indietro</a>");

} // end function prodotto



?>


</body>
</html>
