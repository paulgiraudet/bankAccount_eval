<?php

declare(strict_types = 1);

class AccountManager
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
    public function setDb($_db)
    {
        $this->_db = $_db;

        return $this;
    }


        /**
     * List all accounts
     *
     * @return array $arrayOfAccounts
     */
    public function getAccounts()
    {
        
        $arrayOfAccounts = [];

        $query = $this->getDb()->query('SELECT * FROM accounts ORDER BY balance');
        $dataAccounts = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataAccounts as $dataAccount) {
            $arrayOfAccounts[] = new Account($dataAccount);
        }

        return $arrayOfAccounts;
    }

    public function getAccount($info)
    {
        // get by name
        if (is_string($info))
        {
            $query = $this->getDb()->prepare('SELECT * FROM accounts WHERE name = :name');
            $query->bindValue('name', $info, PDO::PARAM_STR);
            $query->execute();
        }
        // get by id
        elseif (is_int($info))
        {
            $query = $this->getDb()->prepare('SELECT * FROM accounts WHERE id = :id');
            $query->bindValue('id', $info, PDO::PARAM_INT);
            $query->execute();
        }

        $dataAccount = $query->fetch(PDO::FETCH_ASSOC);

        $account = new Account($dataAccount);
        
        return $account;
    }

    /**
     * Add particular account into DB
     *
     * @param [type] $account
     * @return void
     */
    public function add($account)
    {
        $query = $this->getDb()->prepare('INSERT INTO accounts(name, balance) VALUES (:name, :balance)');
        $query->bindValue('name', $account->getName(), PDO::PARAM_STR);
        $query->bindValue('balance', $account->getBalance(), PDO::PARAM_STR);

        $query->execute();

        $id = $this->getDb()->lastInsertId();
        $account->hydrate([
            "id" => $id
        ]);
    }

    /**
     * Delete account from DB
     *
     * @param [type] $account
     */
    public function delete($account)
    {
        $query = $this->getDb()->prepare('DELETE FROM accounts WHERE id = :id');
        $query->bindValue('id', $account->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Update account's data 
     *
     * @param [type] $account
     */
    public function update($account)
    {
        $query = $this->getDb()->prepare('UPDATE accounts SET balance = :balance WHERE id = :id');
        $query->bindValue('balance', $account->getBalance(), PDO::PARAM_INT);
        $query->bindValue('id', $account->getId(), PDO::PARAM_INT);
        $query->execute();
    }
}