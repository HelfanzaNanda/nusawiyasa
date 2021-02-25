<?php

namespace App\Http\Controllers\Slf;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\SLF\SLFTemplate;
use App\Http\Models\GeneralSetting\GeneralSetting;

class SlfTemplateController extends Controller
{
    public function index()
    {
        $tabs = ['BAB I', 'BAB II', 'BAB III', 'BAB IV', 'BAB V'];
        $results = [];
        for ($i=0; $i < count($tabs); $i++) { 
            $data = SLFTemplate::where('name', $tabs[$i])->first();
            array_push($results, [$data, $tabs[$i]]);
        }

        return view('slf.slf_template', [
            'slfTemplates' => $results,
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
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
