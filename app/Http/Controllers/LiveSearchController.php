<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class LiveSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('query');
        $modelClass = $request->query('model');
        $company = Auth::user()->company;

        if (!$query || !$company) {
            return response()->json([]);
        }

        if ($modelClass === \App\Models\Document::class) {
            $results = Document::whereHas('client', function($q) use ($query, $company) {
                    $q->where('company_id', $company->id)
                      ->where('name', 'like', "%{$query}%");
                })
                ->with('client')
                ->limit(10)
                ->get();
        } else {
            $results = [];
        }

        return response()->json($results);
    }
}
