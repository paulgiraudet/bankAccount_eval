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

$manager = new UserManager($db);


if (isset($_POST['addUser'])) {

    // Validation tests
  
    // basic verification on our inputs
    if (isset($_POST['name']) AND !empty($_POST['name']) AND
        isset($_POST['password']) AND !empty($_POST['password']) AND
        isset($_POST['passwordbis']) AND !empty($_POST['passwordbis']) AND
        isset($_POST['email']) AND !empty($_POST['email'])) {
  
            //avoiding any dangerous html tag
            $name = htmlspecialchars($_POST['name']);
            $password = htmlspecialchars($_POST['password']);
            $passwordbis = htmlspecialchars($_POST['passwordbis']);
            $email = htmlspecialchars($_POST['email']);

            // asking in our table if we already have an email with this name
            if (!$manager->checkIfExist($email)) {
                
                // verifying if the two passwords are the same one
                if ($password == $passwordbis) {
                
                    // regex for email verification                    
                    if (preg_match("#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#", $email)) {
                        
                        //crypting password for our database
                        $pass_hache = password_hash($password, PASSWORD_DEFAULT);
                        $user = new User ([
                            "name" => $name,
                            "email" => $email,
                            "password" => $password
                        ]);
                        $manager->add($user);

                        $message = "Vous avez bien été inscrit.";
                    }
                    else {
                        $message = "Votre email est invalide.";
                    }
                }
                else {
                    $message = "Les deux mots de passe ne sont pas identiques.";
                }
            }
            else {
                $message = "Cet email est déjà utilisé, choisissez en un autre.";
            }
  
    }
} //end of isset($_POST['addUser'])

if (isset($_POST['connectUser'])) {

    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    $user = $manager->getUser($email);

    // Compare dbPassword and postPassword
    $isPasswordCorrect = password_verify($password, $user->getPassword());
  
    // checking isset email
    if (!$user) {
        $message = "Mauvais identifiant ou mot de passe !";
    }
    else {
        if ($isPasswordCorrect) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: detail.php');
        }
        else {
            $message = "Mauvais identifiant ou mot de passe !";
        }
    }
}

include "../views/indexView.php";
