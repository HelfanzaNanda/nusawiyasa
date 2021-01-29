<?php

namespace App\Http\Controllers\Slf;

use App\Http\Controllers\Controller;
use App\Http\Models\SLF\SLFTemplate;
use Illuminate\Http\Request;

class SlfTemplateController extends Controller
{
    public function index()
    {
        $tabs = ['BAB I', 'BAB II', 'BAB III', 'BAB IV', 'BAB V'];
        //$datas = SLFTemplate::all();
        $results = [];
        for ($i=0; $i < count($tabs); $i++) { 
            $data = SLFTemplate::where('name', $tabs[$i])->first();
            array_push($results, [$data, $tabs[$i]]);
        }

        //dd($results);
        return view('slf.slf_template', [
            'slfTemplates' => $results
            //'tabs' => ['BAB I', 'BAB II', 'BAB III', 'BAB IV', 'BAB V']
        ]);
    }

    public function store(Request $request)
    {
        SLFTemplate::create([
            'name' => $request->name,
            'template_text' => $request->content
        ]);

        return back()->with('success', 'berhasil menambahkan data slf template');
    }
}
