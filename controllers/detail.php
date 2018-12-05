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

if (isset($_POST['new'])) {
    $name = htmlspecialchars($_POST['name']);
    $account = new Account ([
        "name" => $name,
        "balance" => 80
    ]);
    $manager->add($account);
}

if (isset($_POST['delete'])) {
    $id = (int) $_POST['id'];
    $account = $manager->getAccount($id);
    $manager->delete($account);
}

if (isset($_POST['payment'])) {
    $sum = (int) $_POST['balance'];
    $id = (int) $_POST['id'];
    $account = $manager->getAccount($id);
    $account->addBalance($sum);
    $manager->update($account);
}

if (isset($_POST['debit'])){
    $sum = (int) $_POST['balance'];
    $id = (int) $_POST['id'];
    $account = $manager->getAccount($id);
    $account->removeBalance($sum);
    $manager->update($account);
}

if (isset($_POST['transfer'])) {
    $sum = (int) $_POST['balance'];
    $idDebit = (int) $_POST['idDebit'];
    $accountDebit = $manager->getAccount($idDebit);
    $nameProfit = htmlspecialchars($_POST['idPayment']);
    $accountProfit = $manager->getAccount($nameProfit);

    $accountDebit->accountTransfer($accountProfit, $sum);
    $manager->update($accountDebit);
    $manager->update($accountProfit);

}

$accounts = $manager->getAccounts();

include "../views/detailView.php";
