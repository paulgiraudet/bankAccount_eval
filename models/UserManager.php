<?php

declare(strict_types = 1);

class UserManager
{
    private $_db;

    public function __construct(PDO $db){
        $this->setDb($db);
    }

        

    /**
     * Get the value of _db
     */ 
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * Set the value of _db
     *
     * @return  self
     */ 
    public function setDb(PDO $_db)
    {
        $this->_db = $_db;

        return $this;
    }

    public function checkIfExist(string $email)
    {
        $query = $this->getDb()->prepare('SELECT * FROM members WHERE email = :email');
        $query->bindValue('email', $email, PDO::PARAM_STR);
        $query->execute();

        // If this email exist we return true
        if ($query->rowCount() > 0)
        {
            return true;
        }
        
        // else it doesn't exist yet and we return false
        return false;
    }

    public function getUser($info)
    {
        // get by name
        if (is_string($info))
        {
            $query = $this->getDb()->prepare('SELECT * FROM members WHERE email = :email');
            $query->bindValue('email', $info, PDO::PARAM_STR);
            $query->execute();
        }
        // get by id
        elseif (is_int($info))
        {
            $query = $this->getDb()->prepare('SELECT * FROM members WHERE id = :id');
            $query->bindValue('id', $info, PDO::PARAM_INT);
            $query->execute();
        }

        $dataUser = $query->fetch(PDO::FETCH_ASSOC);

        $user = new User($dataUser);
        
        return $user;
    }

    /**
     * Add particular user into DB
     *
     * @param [type] $user
     * @return void
     */
    public function add(User $user)
    {
        $query = $this->getDb()->prepare('INSERT INTO members(name, email, password) VALUES (:name, :email, :password)');
        $query->bindValue('name', $user->getName(), PDO::PARAM_STR);
        $query->bindValue('email', $user->getEmail(), PDO::PARAM_STR);
        $query->bindValue('password', $user->getPassword(), PDO::PARAM_STR);
        $query->execute();

        $id = $this->getDb()->lastInsertId();
        $user->hydrate([
            "id" => $id
        ]);
    }
}