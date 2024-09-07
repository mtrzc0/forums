<?php

function toTime($t = 0)
{
    if (floor(((time() - strtotime($t)))) < 60) {
        return floor((time() - strtotime($t))) . " seconds ago";
    } else if (floor((time() - strtotime($t))) / 60 < 60) {
        if (floor(((time() - strtotime($t))) / 60) == 1) {
            return floor(((time() - strtotime($t))) / 60) . " minute ago";
        }
        return floor(((time() - strtotime($t))) / 60) . " minutes ago";
    } else if (floor(((time() - strtotime($t))) / 3600) < 24) {
        if (floor(((time() - strtotime($t))) / 3600) == 1) {
            return floor(((time() - strtotime($t))) / 3600) . " hour ago";
        }
        return floor(((time() - strtotime($t))) / 3600) . " hours ago";
    } else if (floor(((time() - strtotime($t))) / (3600 * 24)) < 7) {
        if (floor(((time() - strtotime($t))) / (3600 * 24)) == 1) {
            return floor(((time() - strtotime($t))) / (3600 * 24)) . " day ago";
        }
        return floor(((time() - strtotime($t))) / (3600 * 24)) . " days ago";
    } else if (floor(((time() - strtotime($t))) / (3600 * 24 * 7)) >= 1 && floor(((time() - strtotime($t))) / (3600 * 24 * 7)) <= 4) {
        if (floor(((time() - strtotime($t))) / (3600 * 24 * 7)) == 1) {
            return floor(((time() - strtotime($t))) / (3600 * 24 * 7)) . " week ago";
        }
        return floor(((time() - strtotime($t))) / (3600 * 24 * 7)) . " weeks ago";
    } else if (floor(((time() - strtotime($t))) / (3600 * 24 * date("t"))) < 12) {
        if (floor(((time() - strtotime($t))) / (3600 * 24 * date("t"))) == 1) {
            return floor(((time() - strtotime($t))) / (3600 * 24 * date("t"))) . " month ago";
        }
        return floor(((time() - strtotime($t))) / (3600 * 24 * date("t"))) . " months ago";
    } else if (floor(((time() - strtotime($t))) / (3600 * 24 * 365)) >= 1) {
        if (floor(((time() - strtotime($t))) / (3600 * 24 * 365)) == 1) {
            return floor((time() - strtotime($t)) / (3600 * 24 * 365)) . " year ago";
        }
        return floor(((time() - strtotime($t))) / (3600 * 24 * 365)) . " years ago";
    }
}

function printComment($a = "")
{
    if ($a > 0) {
        if ($a == 1) {
            return $a . " Comment";
        }
        return $a . " Comments";
    } else {
        return 0 . " Comments";
    }
}
