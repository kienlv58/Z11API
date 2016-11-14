<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QueryDB extends Model
{
    public function insertDB($db_name, array $param)
    {
        return $id = DB::table($db_name)->insertGetId($param);
    }

    public function updateDBWithID($db_name, array $param, array $condition, $count_condition)
    {
        $arr_key = array_keys($condition);
        if ($count_condition < 1) {
            return 'erro condition';
        } else if ($count_condition == 1) {
            DB::table($db_name)->where($arr_key[0], $condition[$arr_key[0]])->update($param);
            return 'update succes';
        } else if ($count_condition == 2) {
            DB::table($db_name)->where($arr_key[0], $condition[$arr_key[0]])->where($arr_key[1], $condition[$arr_key[1]])->update($param);
            return 'update succes';
        } else if ($count_condition == 3) {
            DB::table($db_name)->where($arr_key[0], $condition[$arr_key[0]])->where($arr_key[2], $condition[$arr_key[2]])->where($arr_key[3], $condition[$arr_key[3]])->update($param);
            return 'update succes';
        } else {
            return 'count_condition too big';
        }


    }

    public function deteleDBWithID($db_name, $key_id, $id)
    {
        return DB::table($db_name)->where($key_id, $id)->delete();
        //return 'delete succes';
    }

    public function readDBWithID($db_name, array $condition, $count_condition)
    {
        $arr_key = array_keys($condition);
        if ($count_condition < 1) {
            return 'erro condition';
        } else if ($count_condition == 1) {
            return DB::table($db_name)->where($arr_key[0], $condition[$arr_key[0]])->get();
        } else if ($count_condition == 2) {
            return DB::table($db_name)->where($arr_key[0], $condition[$arr_key[0]])->where($arr_key[1], $condition[$arr_key[1]])->get();
        } else if ($count_condition == 3) {
            return DB::table($db_name)->where($arr_key[0], $condition[$arr_key[0]])->where($arr_key[2], $condition[$arr_key[2]])->where($arr_key[3], $condition[$arr_key[3]])->get();
        } else {
            return 'count_condition too big';
        }


    }
}
