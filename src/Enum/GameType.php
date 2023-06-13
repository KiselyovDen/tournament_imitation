<?php

namespace App\Enum;

enum GameType: string
{
    case DIVISION = "DIVISION";
    case QUARTER = "QUARTER";
    case HALF = "HALF";
    case FINAL = "FINAL";
    case BRONZE = "BRONZE";
}