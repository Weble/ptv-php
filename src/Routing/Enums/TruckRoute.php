<?php

namespace PTV\Routing\Enums;

enum TruckRoute: string
{
    case DE_LKWUEBERLSTVAUSNV = "DE_LKWUEBERLSTVAUSNV";
    case NL_LZV = "NL_LZV";
    case NZ_HPMV = "NZ_HPMV";
    case SE_BK_1 = "SE_BK_1";
    case SE_BK_2 = "SE_BK_2";
    case SE_BK_3 = "SE_BK_3";
    case SE_BK_4 = "SE_BK_4";
    case US_STAA = "US_STAA";
    case US_TD = "US_TD";
    case AU_B_DOUBLE = "AU_B_DOUBLE";
    case AU_B_DOUBLE_HML = "AU_B_DOUBLE_HML";
    case AU_B_TRIPLE = "AU_B_TRIPLE";
    case AU_B_TRIPLE_HML = "AU_B_TRIPLE_HML";
    case AU_AB_TRIPLE = "AU_AB_TRIPLE";
    case AU_AB_TRIPLE_HML = "AU_AB_TRIPLE_HML";
    case NONE = "NONE";
}
