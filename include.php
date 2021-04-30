<?php
include('../config/env.php');
foreach (glob('../data/*.php') as $file) {
    include($file);
}



foreach (glob('../control/*.php') as $file) {
    include($file);
}

include ('../page/fct_date.php');
include (__DIR__."/form/Form.php");
include (__DIR__."/form/PropertyForm.php");