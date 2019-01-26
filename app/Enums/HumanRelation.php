<?php

namespace App\Enums;

interface HumanRelation
{
    const FATHER         = 1;
    const MOTHER         = 2;
    const BROTHER        = 3;
    const SISTER         = 4;
    const UNCLE          = 5;
    const AUNT           = 6;
    const MALE_COUSIN    = 7;
    const FEMALE_COUSIN  = 8;
    const LOCAL_GUARDIAN = 9;
    const STEP_FATHER    = 10;
    const STEP_MOTHER    = 11;
    const GRAND_FATHER   = 12;
    const GRAND_MOTHER   = 13;
    const FATHER_IN_LAW  = 14;
    const MOTHER_IN_LAW  = 15;
}
