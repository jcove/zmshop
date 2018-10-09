<?php

namespace App\Http\Controllers;

use App\Models\CaseHistory;
use App\Models\CaseHistoryFiles;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class CaseHistoryController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new CaseHistory();
        $this->setExceptField(["files"]);
    }

    protected function validator($data){
        $rule                               =   [
            'name'                          =>  'required',
            'gender'                        =>  'required',
            'phone'                         =>  'required',
            'content'                       =>  'required',
            'files'                         =>  'required'

        ];
        return Validator::make($data,$rule);
    }

    protected function prepareSave(){
        $this->model->user_id               =   Auth::id();
    }
    protected function where(){
        $where                              =   [];
        if($userId = request()->user_id){
            $where['user_id']               =   $userId;
        }
        return $where;
    }
    private function saved(){
        $this->saveFiles();
    }
    protected function saveFiles(){
        $files                              =   request()->input('files');
        if($files){
            $collection                     =   new Collection();
            foreach ($files as $file){
                $caseFile                   =   new CaseHistoryFiles();
                $caseFile->case_id          =   $this->model->id;
                $caseFile->path             =   $file['path'];
                $caseFile->file_id          =   $file['file_id'];
                $caseFile->save();
                $collection->push($caseFile);
            }
            $this->model->files             =   $collection;
        }
    }

    public function check(){
        $case                               =   CaseHistory::where('user_id',Auth::id())->first();
        if($case){
            return $this->success();
        }else{
            return $this->success(trans('message.data_not_found'));
        }
    }

    protected function beforeIndex(){
        if($this->canJson()){
            $list                               =   $this->data;
            foreach ($list as $key =>$item){

                $item->files                    =   $item->files;
                foreach ($item->files as $k => $v){
                    $v->file                    =   $v->file;
                    $item->files->offsetSet($k,$v);
                }

                $list->offsetSet($key,$item);
            }
            $this->data                         =   $list;
        }


    }
}