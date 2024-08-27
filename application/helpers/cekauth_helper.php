<?php

function checkAuthorize($ticketInfo)
{
    $CI = get_instance();

    $userDirLoggedIn = $CI->session->direktoratid;
    $userCityLoggedIn = $CI->session->city;

    $returnValue = True;

    if ($userCityLoggedIn == "UNIT TEKNIS" && $userDirLoggedIn != $ticketInfo->owner_dir) {
        $returnValue = False;
    }

    if ($userCityLoggedIn != "PUSAT" && $userCityLoggedIn != "UNIT TEKNIS" && $userCityLoggedIn != $ticketInfo->kota) {
        $returnValue = False;
    }

    if ($userDirLoggedIn != '1' && $userDirLoggedIn != $ticketInfo->owner_dir) {
        $returnValue = False;
    }

    return $returnValue;
}
