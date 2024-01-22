<?php

namespace App\Repository;

class Atm extends AbstractTableGateway
{
    public function tableName()
    {
        return "atm";
    }
}
