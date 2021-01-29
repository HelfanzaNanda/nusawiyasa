<?php

namespace App\Http\Controllers\Slf;

use App\Http\Controllers\Controller;
use App\Http\Models\SLF\SLFTemplate;
use Illuminate\Http\Request;

class SlfTemplateController extends Controller
{
    public function index()
    {
        return view('slf.slf_template', [
            'tabs' => ['BAB I', 'BAB II', 'BAB III', 'BAB IV', 'BAB V']
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
