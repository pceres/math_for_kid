<?php





///////////////////////////////////////////////////////////////////////
function text_cifre_decimali($num_dec) {

if ($num_dec == 1) {
    $ks_cifre_decimali = "cifra decimale";
} else {
    $ks_cifre_decimali = "cifre decimali";
}

return $ks_cifre_decimali;

} // end function text_cifre_decimali



///////////////////////////////////////////////////////////////////////
function echo_my($testo,$debug = 0) {

if ($debug) {
    echo($testo);
}
} // end function echo_my



///////////////////////////////////////////////////////////////////////
function var_dump_my($var,$debug = 0) {

if ($debug) {
    var_dump($var);
}
} // end function var_dump_my



///////////////////////////////////////////////////////////////////////
function scrivi($page,$riga,$col,$testo,$debug = 0) {

if (count($page) == 0) {
    $page = Array(
        'off_riga'  => 1,
        'off_col'   => 1,
        'matr'      => Array(Array(' ')),
        );
}


$matr = $page["matr"];

echo_my("<hr>Scrivo \"$testo\" (".strlen($testo)." car.) in ($riga,$col)<br>",$debug);
var_dump_my($page,$debug);
echo_my("<br><br>",$debug);

$num_r = count($matr);
$num_c = count($matr[0]);

$off_riga = $page["off_riga"];
$off_col  = $page["off_col"];

echo_my("matr $num_r x $num_c - off_riga: ".$page["off_riga"]." off_col: ".$page["off_col"]."<br>",$debug);

// position in matr
$ind_riga = $riga-$page["off_riga"]+1;
$ind_col  = $col-$page["off_col"]+1;

echo_my("$ind_riga $ind_col $num_r $num_c<br>",$debug);

if ( $ind_riga < 1 ) {
    // insert rows above
    $off_riga = $page["off_riga"]+$ind_riga-1;
    echo_my("<br>--> adding ".(1-$ind_riga)." rows above<br>",$debug);
    echo_my("new off_riga: $off_riga<br><br>",$debug);

    for ($i = 0; $i < 1-$ind_riga; $i++) {
        echo_my("<br>before ($i):<br>",$debug);
        var_dump_my($matr,$debug);
        array_unshift($matr,Array()); // add one line on top
        echo_my("<br>after adding one line:<br>",$debug);
        var_dump_my($matr,$debug);
        echo_my("<br><br>",$debug);
        for ($j = 0; $j < $num_c; $j++) {
            $matr[0][$j] = " ";
            echo_my("<br>",$debug);
            var_dump_my($matr,$debug);
            echo_my("$i $j<br>",$debug);
        }
    }
    $page["matr"] = $matr;
    $ind_riga = 1;
    $page["off_riga"] = $off_riga;
} elseif ( $ind_riga > $num_r ) {
    // should add new lines in the end
    echo_my("--> add ".$ind_riga-$num_r." lines in the end ($num_r,$ind_riga,$off_col)<br>",$debug);
    for ($i = 0; $i < $ind_riga-$num_r; $i++) {
        $i_riga = $num_r+$i;
        echo_my("riga $i<br><br><br>",$debug);
        $matr[$i_riga] = Array(); // add one row at the end
        for ($j = 0; $j < $num_c; $j++) {
            $matr[$i_riga][$j] = " ";
        }
    var_dump_my($matr,$debug);echo_my("<br>",$debug);
    }
var_dump_my($page,$debug);echo_my("<br>",$debug);
}

if ( $ind_col < 1 ) {
    // expand left
    $off_col = $page["off_col"]+$ind_col-1;
    echo_my("<br>--> adding ".(1-$ind_col)." columns left<br>",$debug);
    echo_my("new off_col: $off_col<br><br>",$debug);
    $num_r = count($matr); // updated number of rows
    echo_my("num_r: $num_r<br><br>",$debug);
    for ($i = 0; $i < $num_r; $i++) {
        $row = $matr[$i];

        for ($j = 0; $j < 1-$ind_col; $j++) {
            array_unshift($row,Array()); // add one char on the left
            $row[0] = " ";
            echo_my("<br>",$debug);
            var_dump_my($row,$debug);
            echo_my("$i $j<br>",$debug);
        }
        echo_my("riga $i:<br>",$debug);
        var_dump_my($row,$debug);echo_my("<br>",$debug);

        $matr[$i] = $row;
    }
    $page["matr"] = $matr;
    $ind_col = 1;
    $page["off_col"] = $off_col;
    var_dump_my($page,$debug);echo_my("<br>",$debug);
}

echo_my("<br>--> writing \"$testo\" in ($riga,$col)<br>",$debug);
$row = $matr[$ind_riga-1];
for ($j = 0; $j < strlen($testo); $j++) {
    $car = substr($testo,$j,1);
    $car = ( (" " == $car) ? '&ensp;' : $car );
    echo_my("car $j: $car<br>",$debug);
    $row[$ind_col+$j-1] = $car;
}
$matr[$ind_riga-1] = $row;
$page["matr"] = $matr;
echo_my("<br>risultato<br>",$debug);
var_dump_my($matr,$debug);
echo_my("<br><br>",$debug);

$page = fix($page,$debug);

if ($debug) {
    stampa($page);
}

return $page;

} // end function scrivi



///////////////////////////////////////////////////////////////////////
function fix($page, $debug = 0) {

$matr = $page["matr"];

$num_r = count($matr); // updated number of rows
$max_c = 0;
for ($i = 0; $i < $num_r; $i++) {
    $row = $matr[$i];
    // $num_c = count($row);
    $num_c = max(array_keys($row));

    if ($num_c > $max_c) {
        $max_c = $num_c;
    }
    echo_my("$i $num_c $max_c<br>",$debug);
}

for ($i = 0; $i < $num_r; $i++) {
    $row = $matr[$i];
    for ($j = 0; $j < $max_c; $j++) {
        if (!isset($row[$j])) {
            $row[$j] = " ";
            echo_my("setting space in $i $j<br>",$debug);
        }
    }
    ksort($row);
    $matr[$i] = $row;
}

$page["matr"] = $matr;
var_dump_my($page,$debug);echo_my("<br>",$debug);

return $page;
}


///////////////////////////////////////////////////////////////////////
function stampa($page) {

$flag_show_row_col = 0;

$matr = $page["matr"];

$off_col  = $page["off_col"];
$off_riga = $page["off_riga"];

// disp(matr)
// var_dump($matr);

// echo("<br>");
// var_dump($page);
// echo("<br>");

$num_r = count($matr); // updated number of rows
$num_c = count($matr[0]);
// echo("num_r: $num_r<br><br>");
echo("<!-- show table -->\n");
echo("<b>\n");
echo("<table>\n");
if ($flag_show_row_col) {
    echo("<tr>\n");
    for ($j = -1; $j < $num_c; $j++) {
        if ($j>=0) {
            $val = $off_col+$j;
        } else {
            $val = ' ';
        }
        echo("<th>$val</th>\n");
    }
    echo("</tr>\n");
}
for ($i = 0; $i <= $num_r; $i++) {
    $row = $matr[$i];
    // var_dump($row);
    echo("<tr>\n\t");
    if ($flag_show_row_col) {
        echo("<td><b>".($off_riga+$i)."</b></td>");
    }
    for ($j = 0; $j <= $num_c; $j++) {
        $car = $row[$j];
        if ($car === " ") {
            $car = "&ensp;";
        }
        echo("<td>$car</td>");
    }
    echo("\n</tr>");
}
echo("\n</table>\n");
echo("</b>\n");

}

?>
