<?php 

function display_errors($errors){
    $display = '<ul class="">';
    foreach($errors as $error){
        $display .= '<li class="alert alert-danger text-center" style="list-style-type: none;">'.$error.'</li>';
    }
    $display .= '</ul>';
    return $display;
}


function sanitize($dirty){
    return htmlentities($dirty,ENT_QUOTES,'UTF-8');
}

function money($number){
    return 'Frw '.number_format($number,2);
}