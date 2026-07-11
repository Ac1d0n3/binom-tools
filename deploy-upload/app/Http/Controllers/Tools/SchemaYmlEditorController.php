<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SchemaYmlEditorController extends Controller
{
    public function show(): View
    {
        return view('tools.schema-yml-editor.show');
    }
}
