<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
//    public  function getFolder($id){
//
//    }
//    public function getAllFolder($take,$skip){
//
//    }
//    public function addFolder(Request $request){
//        $data = $request->toArray();
//    }
//    public function editFolder(Request $request){
//        $data = $request->toArray();
//
//    }
//    public function deleteFolder(Request $request){
//        $data = $request->toArray();
//    }

    protected $model ='App\Folder';

    public  function getFolder($id){
        return $this->getDataById($id);

    }
    public function getAllFolder($take = 'all',$skip = 0){
        return $this->getAllData($this->model,$take,$skip);
    }

    public function addFolder(Request $request){
        return $this->addNewData($this->model,$request->toArray());
    }
    public function editFolder(Request $request){
        return $this->editData($this->model,$request->toArray(),['folder_id',$request->folder_id]);

    }
    public function deleteFolder(Request $request){
        return $this->deleteDataById($this->model,$request->toArray());
    }


}
