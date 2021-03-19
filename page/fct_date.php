<?php

function date_us2fr($date){
    list($year,$month,$day) = explode('-',$date);
    return $day.'/'.$month.'/'.$year;
}
function datetime_us2fr($datetime){
    list($date,$time) = explode(' ',$datetime);
    list($year,$month,$day) = explode('-',$date);
    return $day.'/'.$month.'/'.$year.' '.$time;
}