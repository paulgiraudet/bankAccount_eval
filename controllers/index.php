<?php

// On enregistre notre autoload.
function chargerClasse($classname)
{
    if(file_exists('../models/'. $classname.'.php'))
    {
        require '../models/'. $classname.'.php';
    }
    else 
    {
        require '../entities/' . $classname . '.php';
    }
}
spl_autoload_register('chargerClasse');

$db = Database::DB();

$manager = new AccountManager($db);

include "../views/indexView.php";
