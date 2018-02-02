<?php
namespace App\Http\Dao;

use App\Http\Dto\PersonalDto;
use Illuminate\Support\Facades\DB;

class PersonalDao
{
    public function getPersons($name) {
        $persons = null;
        if($name==null){
            $persons = PersonalDto::paginate(5);
        }else{
            $persons = PersonalDto::where('nickname','like','%'.$name.'%')
                ->paginate(5);
        }
        return $persons;
    }
}