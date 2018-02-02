<?php
namespace App\Http\Service;

use App\Http\Dao\PersonalDao;

class PersonalService
{
    public function getPersons($name) {
        $personalDto = new PersonalDao();
        return $personalDto->getPersons($name);
    }
}