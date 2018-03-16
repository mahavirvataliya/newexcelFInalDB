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
    return view('importExport');
    }
    public function downloadExcel($type)
    {
    // $data = Item::get()->toArray();
    // return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
    // $excel->sheet('mySheet', function($sheet) use ($data)
    //         {
    // $sheet->fromArray($data);
    //         });
    // })->download($type);
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
    Schema::create($fileName.'s', function (Blueprint $table) use ($columns) {
        $table->increments('id');
        foreach($columns as $column)
        {
            $table->string($column);
        }
    });
    
    Artisan::call('make:model',['name'=>$fileName]);
    $insert =[];

    if(!empty($data) && $data->count()){
    foreach ($data as $key => $value) {
        foreach($columns as $column)
        {
           $insert[$column] = $value->$column.'';
        }
        if(!empty($insert)){
            DB::table($fileName.'s')->insert($insert);
            }
    }
    }
    $data = DB::table($fileName.'s')->select('*')->get()->toarray();
    dd('Insert Record successfully.');
    return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
    $excel->sheet('mySheet', function($sheet) use ($data)
            {
    $sheet->fromArray($data);
            });
    })->download($type);
    dd(DB::table($fileName.'s')->select('*')->get());

    dd('Insert Record successfully.');
 
    }
    return back();
    }
}
