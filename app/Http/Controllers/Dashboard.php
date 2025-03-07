<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Routing\Controller as BaseController;
use App\Models\student;


class Dashboard extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    public function view(){
        $db = new student;

        

        $data = [
            'siswa' => $db->paginate(10)
        ];
        return view('data-santri', $data);
    }
    public function store(Request $request){
        $file = $request->file('sheet')->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $sheet = $reader->load($file)->getActiveSheet()->toArray();

        for ($i=1; $i < count($sheet)-1; $i++) { 
            $db = new student;
            $db::create([
                'nis'       => null,
                'nama'      => $sheet[$i][1],
                'no_kk'     => $sheet[$i][2],
                'no_nik'    => $sheet[$i][3],
                'nisn'      => $sheet[$i][4],
                'kelamin'   => $sheet[$i][5],
                'ibu'       => $sheet[$i][10],
                'ayah'      => $sheet[$i][9],
                'tptlahir'  => $sheet[$i][6],
                'tgllahir'  => $sheet[$i][7],
                'alamat'    => $sheet[$i][8],
                'kamar'     => $sheet[$i][15],
                'kls_formal'=> $sheet[$i][14],
                'kls_diniyah'=> $sheet[$i][13],
                'hp_ayah'   => $sheet[$i][11],
                'hp_ibu'    => $sheet[$i][12]
            ]);
            
        }
        return redirect('dashboard/view/');
    }
    public function search(Request $r){
        if($r->type == 'nama'){
            $db = new student;

            $data = [
                'siswa' => $db->where('nama', 'like', "%" . $r->text . "%")->paginate(10)
            ];
            
            return view('data-santri', $data);
        }else{
            $db = new student;

            $data = [
                'siswa' => $db->where('nis', 'like', "%" . $r->text . "%")->paginate(10)
            ];
            
            return view('data-santri', $data);
        }
    }

}
