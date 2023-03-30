<?php
namespace App\Models;
use Symfony\Component\Serializer\Annotation\Groups;
class HausseIndice{
    function __construct() {
    }
    
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $client;
    
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $operationLabel;
    
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $PrixUnitaire;
    
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $idBdcO;
}