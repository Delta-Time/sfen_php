<?php

include_once "../sfen2array.php";

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    function testChar2Code()
    {
        $codes = [
            "P", "L", "N", "S", "G", "B", "R", "K",
            "p", "l", "n", "s", "g", "b", "r", "k",
            "+P", "+L", "+N", "+S", "+G", "+B", "+R", "+K",
            "+p", "+l", "+n", "+s", "+g", "+b", "+r", "+k",
            "NYA"];
        $returns = [
            [FU, SENTE], [KYO, SENTE], [KEI, SENTE], [GIN, SENTE], [KIN, SENTE], [KAKU, SENTE], [HISHA, SENTE], [OU, SENTE],
            [FU, GOTE], [KYO, GOTE], [KEI, GOTE], [GIN, GOTE], [KIN, GOTE], [KAKU, GOTE], [HISHA, GOTE], [OU, GOTE],
            [TOKIN, SENTE], [NARIKYOU, SENTE], [NARIKEI, SENTE], [NARIGIN, SENTE], [NULL_KOMA, NULL_TEBAN], [UMA, SENTE], [RYU, SENTE], [NULL_KOMA, NULL_TEBAN],
            [TOKIN, GOTE], [NARIKYOU, GOTE], [NARIKEI, GOTE], [NARIGIN, GOTE], [NULL_KOMA, NULL_TEBAN], [UMA, GOTE], [RYU, GOTE], [NULL_KOMA, NULL_TEBAN],
            [NULL_KOMA, NULL_TEBAN]
        ];

        $counter = 0;
        foreach ($codes as $c) {
            $this->assertSame($returns[$counter], char2code($c));
            $counter++;
        }
    }

    function testKoma2img()
    {
        $komas = [[FU, SENTE], [NARIKYOU, GOTE], [UMA, SENTE], [OU, GOTE], [NULL_KOMA, NULL_TEBAN]];
        $returns = ["FU.png", "vKYr.png", "KAr.png", "vOU.png", "NIL.png"];

        $counter = 0;
        foreach ($komas as $k) {
            $this->assertSame($returns[$counter], koma2img($k));
            $counter++;
        }
    }

    function _baseBoardProvide(): array
    {
        $board[] = [
            [KYO, GOTE], [KEI, GOTE], [GIN, GOTE], [KIN, GOTE], [OU, GOTE], [KIN, GOTE], [GIN, GOTE], [KEI, GOTE], [KYO, GOTE]
        ];
        $board[] = [
            [NULL_KOMA, NULL_TEBAN], [HISHA, GOTE], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [KAKU, GOTE], [NULL_KOMA, NULL_TEBAN]
        ];
        $board[] = array_fill(0, 9, [FU, GOTE]);
        $board[] = array_fill(0, 9, [NULL_KOMA, NULL_TEBAN]);
        $board[] = array_fill(0, 9, [NULL_KOMA, NULL_TEBAN]);
        $board[] = array_fill(0, 9, [NULL_KOMA, NULL_TEBAN]);
        $board[] = array_fill(0, 9, [FU, SENTE]);
        $board[] = [
            [NULL_KOMA, NULL_TEBAN], [KAKU, SENTE], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [NULL_KOMA, NULL_TEBAN], [HISHA, SENTE], [NULL_KOMA, NULL_TEBAN]
        ];
        $board[] = [
            [KYO, SENTE], [KEI, SENTE], [GIN, SENTE], [KIN, SENTE], [OU, SENTE], [KIN, SENTE], [GIN, SENTE], [KEI, SENTE], [KYO, SENTE]
        ];

        return $board;
    }

    function testBaseBoard()
    {
        //先手後手の区別なく
        $board_kanji = [
            ["香", "桂", "銀", "金", "王", "金", "銀", "桂", "香"],
            ["　", "飛", "　", "　", "　", "　", "　", "角", "　"],
            ["歩", "歩", "歩", "歩", "歩", "歩", "歩", "歩", "歩"],
            ["　", "　", "　", "　", "　", "　", "　", "　", "　"],
            ["　", "　", "　", "　", "　", "　", "　", "　", "　"],
            ["　", "　", "　", "　", "　", "　", "　", "　", "　"],
            ["歩", "歩", "歩", "歩", "歩", "歩", "歩", "歩", "歩"],
            ["　", "角", "　", "　", "　", "　", "　", "飛", "　"],
            ["香", "桂", "銀", "金", "王", "金", "銀", "桂", "香"],
        ];

        $codes = Test::_baseBoardProvide();
        foreach (range(0, 8) as $x) {
            foreach (range(0, 8) as $y) {
//                print("[{$x}, {$y}] {$codes[$x][$y][0][1]} | {$board_kanji[$x][$y]}".PHP_EOL);
                $this->assertSame($codes[$x][$y][0][1], $board_kanji[$x][$y]);
            }
        }

        // 先後と駒のテスト
        $this->assertSame($codes[0][0], [KYO, GOTE]);
        $this->assertSame($codes[0][4], [OU, GOTE]);
        $this->assertSame($codes[7][1], [KAKU, SENTE]);
        $this->assertSame($codes[8][6], [GIN, SENTE]);
    }

    function testSfen2Array()
    {
        $sfen = "lnsgkgsnl/1r5b1/ppppppppp/9/9/9/PPPPPPPPP/1B5R1/LNSGKGSNL w - 1";
        $board = $this->_baseBoardProvide();

        $this->assertSame($board, sfen2array($sfen));

    }
}
