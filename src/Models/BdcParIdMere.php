<?php
use App\Models\HausseIndice;
namespace App\Models;
use Symfony\Component\Serializer\Annotation\Groups;

class BdcParIdMere{
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $idMere;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $bdcOpe;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $numBdc;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $idBdc;
    function __construct() {
    }
}