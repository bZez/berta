<?php

namespace App\Http\Controllers;

use App\Sections;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function create(Request $request) {
        $json = $request->json()->all();
        $sections = new Sections($json['site']);
        $section = $sections->create($json);

        return response()->json($section);
    }

    public function order(Request $request) {
        $sections = new Sections();
        $json = $request->json()->all();
        $sections->order($json['site'], $json['sections']);
        return response()->json($json);
    }
}
