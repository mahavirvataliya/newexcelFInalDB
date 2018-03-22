<?php

namespace App\Http\Controllers;
use Artisan;
use Illuminate\Http\Request;
use DB;
use Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class MaatwebsiteDemoController extends Controller
{
    public function importExport()
    {
        $path = app_path() ;

function getModels($path){
    $out = [];
    $results = scandir($path);
    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
        $filename = $path . '/' . $result;
        if (is_dir($filename)) {
            $out = array_merge($out, getModels($filename));
        }else{
            $out[] = substr($filename,0,-4);
        }
    }
    return $out;
}

dd(getModels($path));

        $message = '';
        $tables = DB::select('SHOW TABLES'); 
       
    return view('importExport',compact(['tables','message']));
    }
    public function downloadExcel($type)
    {
        
    }
    public function exportxls(Request $request)
    {
        $model = substr($request->tablename, 0, -1);
        $my_var = '\App\\'.ucfirst($model);
        $cclas = new $my_var;
       $data1 = $cclas::get()->toArray();
        return Excel::create($model, function($excel) use ($data1) {
        $excel->sheet('mySheet', function($sheet) use ($data1)
                {
                     $sheet->fromArray($data1);
                });
        })->download('xls');
    }
    public function importExcel(Request $request)
    {
    if(Input::hasFile('import_file')){
    $path = Input::file('import_file')->getRealPath();

    $filen = Input::file('import_file')->getClientOriginalName();
    $fileName = substr($filen, 0 , (strrpos($filen, ".")));   

    $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
    })->get();

    //dd($data->first()->firstname);
    $columns = array_keys($data->first()->toarray());
    //dd($columns);

    Artisan::call('make:model',['name'=>ucfirst($fileName)]);
    $my_var = '\App\\'.ucfirst($fileName);
    $cclas = new $my_var;
    $tablename = $cclas->getTable();

    Schema::create($tablename, function (Blueprint $table) use ($columns) {
        $table->increments('id');
        foreach($columns as $column)
        {
            $table->string($column);
        }
    });
    
   
    $insert =[];

    if(!empty($data) && $data->count()){
    foreach ($data as $key => $value) {
        foreach($columns as $column)
        {
           $insert[$column] = $value->$column.'';
        }
        if(!empty($insert)){
            DB::table($tablename)->insert($insert);
            }
    }
    }
   
    $my_var = '\App\\'.ucfirst($fileName);
    $cclas = new $my_var;
   $data1 = $cclas::get()->toArray();
  

    $message = 'Insert Record successfully.';
    $tables = DB::select('SHOW TABLES'); 

    
        


    return view('importExport',compact(['tables','message']));
    }
    return back();
    }
}
