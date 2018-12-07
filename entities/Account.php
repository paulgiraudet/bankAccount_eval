<?php

declare(strict_types = 1);

class Account
{
    protected   $id,
                $name,
                $balance,
                $firstBalance,
                $id_user;
    
    public function __construct(array $array){
        $this->hydrate($array);
    }

    /**
     * Hydratation
     *
     * @param array $donnees
     */
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set'.ucfirst($key);
                
            // Si le setter correspondant existe.
            if (method_exists($this, $method))
            {
                // On appelle le setter.
                $this->$method($value);
            }
        }
    }           

    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $id = (int) $id;
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of balance
     */ 
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Get the value of id_user
     */ 
    public function getId_user()
    {
        return $this->id_user;
    }

    /**
     * Set the value of id_user
     *
     * @return  self
     */ 
    public function setId_user($id_user)
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * Set the value of balance
     *
     * @return  self
     */ 
    public function setBalance($balance)
    {
        $balance = (int) $balance;
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get the value of firstBalance
     */ 
    public function getFirstBalance()
    {
        return $this->firstBalance;
    }

    /**
     * Set the value of firstBalance
     *
     * @return  self
     */ 
    public function setFirstBalance($firstBalance)
    {
        $this->firstBalance = $firstBalance;

        return $this;
    }

    public function addBalance(int $sum){
        $newBalance = $this->getBalance() + $sum;
        $this->setBalance($newBalance);
    }
    public function removeBalance(int $sum){
        $newBalance = $this->getBalance() - $sum;
        $this->setBalance($newBalance);
    }

    public function accountTransfer(Account $account, int $sum, int $newSum){
        $this->removeBalance($sum);
        $account->addBalance($newSum);
    }

}
