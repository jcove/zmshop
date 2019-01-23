<?php
/**
 * User: XiaoFei Zhai
 * Date: 17/9/27
 * Time: 下午5:58
 */

namespace App\Http\Controllers;


use App\Models\File;
use Illuminate\Http\Request;
use Jcove\Restful\Restful;


class FileController extends Controller
{
    /**
     * Storage instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    use Restful;

    public function upload(Request $request){
        if ($request->hasFile('file')) {

            if($request->file('file')->isValid()){
                $md5                            =   md5_file($request->file('file'));
                $fileModel                      =   new File();
                $file                           =   $fileModel->getByMd5($md5);
                if($file){
                    return response()->json($file);
                }else{
                    $path = $request->file->store('images');
                    $file                           =   new File();
                    $file->original_name            =   $request->file->getClientOriginalName();
                    $file->path                     =   $path;
                    $file->ext                      =   $request->file->getClientOriginalExtension();
                    $file->name                     =   $request->file->hashName();
                    $file->md5                      =   $md5;
                    $file->size                     =   $request->file->getSize();
                    $file->save();
                    return response()->json($file);
                }

            }else{
                return response('文件无效',500);
            }
        }else{
            return response('请选择上传的文件',500);
        }
    }

    public function download($id){
        $file                                       =   File::findOrFail($id);
        $path                                       =   config('filesystems.disks.'.config('filesystems.default').'.root').'/';
        return response()->download($path.$file->path, $file->original_name);
    }

}