<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SecondDatabaseConnexion {
    private $conn;

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }


    public function connexion(){
        $connectionParams = [
            'url' => 'mysql://'.$this->params->get('username_bdd_tana_paris').':'.$this->params->get('password_bdd_tana_paris').'@'.$this->params->get('host_bdd_tana_paris').'/'.$this->params->get('db_name_tana_paris'),
        ];

        $this->conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

        return $this->conn;
    }
}