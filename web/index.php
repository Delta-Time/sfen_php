<?php
    include_once "../sfen2array.php"
?>
<!doctype html>

<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Sfen to PHP</title>
</head>

<body>
<table>
    <?php
        $board = sfen2array("l3k2G1/1+B4gPl/n2+Nppsp1/pP2R2bp/9/Pps1P1N1P/2GG1P3/3S5/LNK5L b R6Ps 97");

        foreach (range(0,8) as $x){
            echo "<tr>";
            foreach (range(0,8) as $y){
                $koma_uri = "./resources/".koma2img($board[$x][$y]);
                echo "<td>";
                echo "<img src='{$koma_uri}' width='50px' height='50px'>";
                echo "</td>";
            }
            echo "</tr>";
        }
    ?>
</table>
</body>
</html>