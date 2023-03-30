<?php
use App\Models\HausseIndice;
namespace App\Models;
use Symfony\Component\Serializer\Annotation\Groups;

class BdcOperationPerClient{
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $raisonSocial;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $ligne;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $bdcParIdmere;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $valide;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $idCustomer;
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $contact = [];
     /**
      *  @Groups({"BdcOperationPerClient", "status:lead", "input"})
     */
    public $statusBdc;
    function __construct() {
    }
}