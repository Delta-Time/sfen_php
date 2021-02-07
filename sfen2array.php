<?php
// [0] = 画像名識別, [1] = 一般略表示, [2] = 一般名表示, [3] = sfen先手, [4] = sfen後手
// 画像識別名: 後手(上下逆)は v を先頭につける。ファイル名は "v{TOKIN[0]}.png" など。
const FU    = ["FU", "歩", "歩兵", "P", "p"];
const KYO   = ["KY", "香", "香車", "L", "l"];
const KEI   = ["KE", "桂", "桂馬", "N", "n"];
const GIN   = ["GI", "銀", "銀将", "S", "s"];
const KIN   = ["KI", "金", "金将", "G", "g"];
const KAKU  = ["KA", "角", "角行", "B", "b"];
const HISHA = ["HI", "飛", "飛車", "R", "r"];
const OU    = ["OU", "王", "王将", "K", "k"];

const TOKIN     = ["FUr", "と", "と金", "+P", "+p"];
const NARIKYOU  = ["KYr", "杏", "成香", "+L", "+l"];
const NARIKEI   = ["KEr", "圭", "成桂", "+N", "+n"];
const NARIGIN   = ["GIr", "全", "成銀", "+S", "+s"];
const UMA       = ["KAr", "馬", "竜馬", "+B", "+b"];
const RYU       = ["HIr", "龍", "龍王", "+R", "+r"];

const NULL_KOMA = ["NIL", "　", "　　", "_", "+_"];

const NULL_TEBAN = 0;
const SENTE = 1;
const GOTE  = 2;

const ALL_KOMA = [FU, KYO, KEI, GIN, KIN, KAKU, HISHA, OU, TOKIN, NARIKYOU, NARIKEI, NARIGIN, UMA, RYU, NULL_KOMA];

function char2code($c): array{
    foreach (ALL_KOMA as $koma){
        if($c === $koma[3]){
            return [$koma, SENTE];
        }
        if($c === $koma[4]){
            return [$koma, GOTE];
        }
    }
    return [NULL_KOMA, NULL_TEBAN];
}

function koma2img($koma_array): string{
    $img_uri  = ( $koma_array[1] === GOTE ? "v" : "" );
    $img_uri .= $koma_array[0][0];
    $img_uri .= ".png";

    return $img_uri;
}

function sfen2array($sfen){
    $caret = 0;
    $board_array = array_fill(0,9,array_fill(0, 9, [NULL_KOMA, NULL_TEBAN]));
    $x = 0;
    $y = 0;

    while(1){
        $skip_caret = 0;
        $is_skip = false;
        $char = substr($sfen, $caret, 1);

        switch($char){
            case "+":
                $char = substr($sfen, $caret, 2);
                $skip_caret = 2;
                break;

            case "1":
            case "2":
            case "3":
            case "4":
            case "5":
            case "6":
            case "7":
            case "8":
            case "9":
                $skip_caret = 1;
                $y += intval($char);
                $is_skip = true;
                break;

            case "/":
                $skip_caret = 1;
                $y = 0;
                $x ++;
                $is_skip = true;
                break;

            case " ":
                return $board_array;

            default:
                $skip_caret = 1;
                break;
        }

        if(!$is_skip) {
            $board_array[$x][$y] = char2code($char);
            $y++;
        }
        $caret += $skip_caret;
    }
}
