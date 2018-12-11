<?php

// Saving our autoload
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

//keeping session active for our user
session_start();

$db = Database::DB();

$manager = new AccountManager($db);

//reseting our error message if all is good
$message = false;

//if we are not connected to the app we get redirected to the connection page
//else we save the user data in a variable
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}
else {
    $user = $_SESSION['user'];
}


//if we want to create a new account
if (isset($_POST['new'])) {
    $name = htmlspecialchars($_POST['name']);

    //we save all existant accounts for this user and we check if there already is an account with the name used for account creation
    $accounts = $manager->getAccounts($user->getId());
    foreach ($accounts as $account) {
        if ($account->getName() == $name) {
            $message = "Compte déjà existant.";
        }
    }

    //if there is no account with this $name we can add it
    if (!$message) {
        

        //particular $balance ONLY for "Compte courant"
        if ($name == "Compte courant") {
            $balance = 80;
        }
        else {
            $balance = 0;
        }

        //object hydratation
        $account = new Account ([
            "name" => $name,
            "balance" => $balance,
            "firstBalance" => 0,
            "id_user" => $user->getId()
        ]);

        //our manager save it in our database
        $manager->add($account);

        header('Location: detail.php');
    }
}


//if we want to delete an account
if (isset($_POST['delete'])) {
    $id = (int) $_POST['id'];

    //getting the specific account from our database with his particular id, then we delete it with our manager
    $account = $manager->getAccount($id);
    $message = "Votre compte " . $account->getName() . " a bien été supprimé.";
    $manager->delete($account);
}


//if we want to add balance to a particular account
if (isset($_POST['payment'])) {
    $sum = (int) $_POST['balance'];
    $id = (int) $_POST['id'];

    //getting the particular account with his id
    $account = $manager->getAccount($id);

    //this occurs ONLY if its the first add on a "Livret A"
    if ($account->getFirstBalance() == 0 && $account->getName() == "Livret A") {
        $newSum = $sum + $sum * 2.5/100;
        $account->setFirstBalance(1);
        $sum = $newSum;
    }

    //basic update with new balance account
    $account->addBalance($sum);
    $manager->update($account);

    header('Location: detail.php');
}


//if we want to remove balance from a particular account
if (isset($_POST['debit'])){
    $sum = (int) $_POST['balance'];
    $id = (int) $_POST['id'];

    //getting the particular account with his id
    $account = $manager->getAccount($id);

    //avoiding any balance from a "PEL"
    if ($account->getName() == "PEL") {
        $message = "Aucun retrait possible à partir d'un PEL, l'argent y est placé.";
    }
    //else we update the account with his new balance
    else{
        $account->removeBalance($sum);
        $manager->update($account);

        header('Location: detail.php');
    }
}


//if we want to tranfer balance from an account to another
if (isset($_POST['transfer'])) {
    $sum = (int) $_POST['balance'];
    $idDebit = (int) $_POST['idDebit'];

    //getting the origin account
    $accountDebit = $manager->getAccount($idDebit);

    //avoiding any transfer from a "PEL"
    if ($accountDebit->getName() == "PEL") {
        $message = "Aucun virement possible depuis un PEL, l'argent y est bloqué.";
    }
    else {        
        $idProfit = (int) $_POST['idPayment'];

        //getting the target account
        $accountProfit = $manager->getAccount($idProfit);
        $newSum = $sum;

        //if its the first transfert on a "Livret A" we add a certain percentage
        if ($accountProfit->getFirstBalance() == 0 && $accountProfit->getName() == "Livret A") {
            $newSum = $sum + $sum * 2.5/100;
            $accountProfit->setFirstBalance(1);
        }

        //transfering balance and updating both accounts
        $accountDebit->accountTransfer($accountProfit, $sum, $newSum);
        $manager->update($accountDebit);
        $manager->update($accountProfit);
    
        header('Location: detail.php');
    }
}


//if we want to disconnect 
if (isset($_POST['disconnection'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}


//listing all accounts from a particular user in an array
$accounts = $manager->getAccounts($user->getId());

include "../views/detailView.php";
