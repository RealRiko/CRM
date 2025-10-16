<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

trait LiveSearchTrait
{
    /**
     * Handles live search requests for a given model.
     *
     * @param Request $request
     * @param string $modelClass The full class name of the Eloquent model (e.g., App\Models\Client::class).
     * @return JsonResponse
     */
    protected function performLiveSearch(Request $request, string $modelClass): JsonResponse
    {
        $search = $request->query('query');
        $company = Auth::user()->company;

        if (!$search || !$company) {
            return response()->json([]);
        }

        /** @var Model $model */
        $model = new $modelClass();
        
        $results = $model::query()
            ->where('company_id', $company->id)
            ->where(function (Builder $query) use ($search) {
                // Check if the model has a 'name' field
                if (method_exists($model, 'hasNamedField') && $model->hasNamedField('name')) {
                    $query->where('name', 'like', "%{$search}%");
                }
                
                // Add more specific fields for common models
                if ($model instanceof \App\Models\Client) {
                    $query->orWhere('email', 'like', "%{$search}%");
                }
                
                // Add other generic fields if they exist
                if (method_exists($model, 'hasNamedField') && $model->hasNamedField('category')) {
                    $query->orWhere('category', 'like', "%{$search}%");
                }
            })
            // Select only necessary fields for faster API response
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}