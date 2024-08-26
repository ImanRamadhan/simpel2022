<?php

function checkAuthorize($ticketInfo)
{
    $CI = get_instance();

    $useridLoggedIn = $CI->session->id;
    $userDirLoggedIn = $CI->session->direktoratid;

    $returnValue = False;
    if ($CI->session->city != 'PUSAT') {
        if ($useridLoggedIn == $ticketInfo->owner) {
            $returnValue = true;
        } else if ($userDirLoggedIn == $ticketInfo->owner_dir) {
            $returnValue = true;
        } else {
            if ($userDirLoggedIn == $ticketInfo->direktorat) {
                $returnValue = true;
            }

            if ($userDirLoggedIn == $ticketInfo->direktorat2) {
                $returnValue = true;
            }

            if ($userDirLoggedIn == $ticketInfo->direktorat3) {
                $returnValue = true;
            }

            if ($userDirLoggedIn == $ticketInfo->direktorat4) {
                $returnValue = true;
            }

            if ($userDirLoggedIn == $ticketInfo->direktorat5) {
                $returnValue = true;
            }
        }
    } else {
        $returnValue = true;
    }

    return $returnValue;
}
