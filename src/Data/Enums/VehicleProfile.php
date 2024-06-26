<?php

namespace PTV\Data\Enums;

enum VehicleProfile: string
{
    case EUR_TRAILER_TRUCK = "EUR_TRAILER_TRUCK";
    case EUR_TRUCK_40T = "EUR_TRUCK_40T";
    case EUR_TRUCK_11_99T = "EUR_TRUCK_11_99T";
    case EUR_TRUCK_7_49T = "EUR_TRUCK_7_49T";
    case EUR_VAN = "EUR_VAN";
    case EUR_CAR = "EUR_CAR";
    case EUR_TLN_TRUCK_40T = "EUR_TLN_TRUCK_40T";
    case EUR_TLN_TRUCK_20T = "EUR_TLN_TRUCK_20T";
    case EUR_TLN_TRUCK_11_99T = "EUR_TLN_TRUCK_11_99T";
    case EUR_TLN_VAN = "EUR_TLN_VAN";
    case USA_8_SEMITRAILER_5AXLE = "USA_8_SEMITRAILER_5AXLE";
    case USA_5_DELIVERY = "USA_5_DELIVERY";
    case USA_1_PICKUP = "USA_1_PICKUP";
    case AUS_HR_HEAVY_RIGID = "AUS_HR_HEAVY_RIGID";
    case AUS_MR_MEDIUM_RIGID = "AUS_MR_MEDIUM_RIGID";
    case AUS_LCV_LIGHT_COMMERCIAL = "AUS_LCV_LIGHT_COMMERCIAL";
    case IMEA_TRUCK_40T = "IMEA_TRUCK_40T";
    case IMEA_TRUCK_7_49T = "IMEA_TRUCK_7_49T";
    case IMEA_VAN = "IMEA_VAN";
    case IMEA_CAR = "IMEA_CAR";
    case BICYCLE = "BICYCLE";
    case PEDESTRIAN = "PEDESTRIAN";
}
