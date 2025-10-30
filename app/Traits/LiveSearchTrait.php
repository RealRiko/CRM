<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

trait LiveSearchTrait
{
    /**
     * Perform a live search for any model
     *
     * @param Request $request
     * @param string $modelClass Fully qualified model class (e.g., App\Models\Client)
     * @param array $searchFields Fields to search in (e.g., ['name', 'email'])
     * @return JsonResponse
     */
    protected function performLiveSearch(Request $request, string $modelClass, array $searchFields = ['name'])
    {
        $search = trim($request->query('query', ''));
        $company = Auth::user()->company;

        if (!$search || !$company) {
            return response()->json([]);
        }

        /** @var Model $model */
        $model = new $modelClass();

        $query = $model::query()
            ->where('company_id', $company->id)
            ->where(function ($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            })
            ->select('*', DB::raw("
                CASE
                    WHEN name = '{$search}' THEN 100
                    WHEN name LIKE '{$search}%' THEN 90
                    WHEN name LIKE '%{$search}%' THEN 80
                    ELSE 0
                END AS relevance
            "))
            ->orderByDesc('relevance')
            ->limit(10);

        return response()->json($query->get());
    }
}

