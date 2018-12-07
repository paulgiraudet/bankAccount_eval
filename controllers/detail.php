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

session_start();

$db = Database::DB();

$manager = new AccountManager($db);

$message = false;

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}
else {
    $user = $_SESSION['user'];
}

if (isset($_POST['new'])) {
    $name = htmlspecialchars($_POST['name']);
    $accounts = $manager->getAccounts($user->getId());

    foreach ($accounts as $account) {
        if ($account->getName() == $name) {
            $message = "Compte déjà existant.";
        }
    }
    if (!$message) {
        
        if ($name == "Compte courant") {
            $balance = 80;
        }
        else {
            $balance = 0;
        }
        $account = new Account ([
            "name" => $name,
            "balance" => $balance,
            "firstBalance" => 0,
            "id_user" => $user->getId()
        ]);
        $manager->add($account);
        header('Location: detail.php');
    }
}


if (isset($_POST['delete'])) {
    $id = (int) $_POST['id'];
    $account = $manager->getAccount($id);
    $manager->delete($account);
    header('Location: detail.php');
}

if (isset($_POST['payment'])) {
    $sum = (int) $_POST['balance'];
    $id = (int) $_POST['id'];
    $account = $manager->getAccount($id);

    if ($account->getFirstBalance() == 0 && $account->getName() == "Livret A") {
        $newSum = $sum + $sum * 2.5/100;
        $account->setFirstBalance(1);
        $sum = $newSum;
    }

    $account->addBalance($sum);
    $manager->update($account);
    header('Location: detail.php');
}

if (isset($_POST['debit'])){
    $sum = (int) $_POST['balance'];
    $id = (int) $_POST['id'];
    $account = $manager->getAccount($id);
    if ($account->getName() == "PEL") {
        $message = "Aucun retrait possible à partir d'un PEL, l'argent y est placé.";
    }
    else{
        $account->removeBalance($sum);
        $manager->update($account);
        header('Location: detail.php');
    }
}

if (isset($_POST['transfer'])) {
    $sum = (int) $_POST['balance'];
    $idDebit = (int) $_POST['idDebit'];
    $accountDebit = $manager->getAccount($idDebit);
    if ($accountDebit->getName() == "PEL") {
        $message = "Aucun virement possible depuis un PEL, l'argent y est bloqué.";
    }
    else {        
        $idProfit = (int) $_POST['idPayment'];
        $accountProfit = $manager->getAccount($idProfit);
        $newSum = $sum;
        if ($accountProfit->getFirstBalance() == 0 && $accountProfit->getName() == "Livret A") {
            $newSum = $sum + $sum * 2.5/100;
            $accountProfit->setFirstBalance(1);
        }
        $accountDebit->accountTransfer($accountProfit, $sum, $newSum);
        $manager->update($accountDebit);
        $manager->update($accountProfit);
    
        header('Location: detail.php');
    }
}

if (isset($_POST['disconnection'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

$accounts = $manager->getAccounts($user->getId());

include "../views/detailView.php";
