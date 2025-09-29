<?php

namespace App\Http\Controllers\Api\ElasticSearch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Helpers\ResponseHelper;
use App\Http\Resources\ElasticSearch\ElasticProductResource;

class SearchController extends Controller
{
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;

    public function __construct(ElasticSearchTypeService $elasticSearchTypeService, ElasticSearchProductService $elasticSearchProductService)
    {
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
    }

    public function search(Request $request)
    {
        $query   = $request->input('q', '');
        $filters = $this->elasticSearchTypeService->filterType($request);
        $sorting = $this->elasticSearchTypeService->sortingType($request);

        $data = $this->elasticSearchProductService->searchProducts(
            $query,
            $filters,
            $sorting,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        if (!empty($data['products'])) {
            return response()->json([
                'total'    => $data['results']['total'],
                'page'     => $request->input('page', 1),
                'size'     => $request->input('size', 12),
                'query'    => $query ?? null,
                'products' => ElasticProductResource::collection($data['products']),
            ]);
        }

        return ResponseHelper::notFound('Ürün bulunamadı.', [
            'total'    => 0,
            'page'     => $request->input('page', 1),
            'size'     => $request->input('size', 12),
            'query'    => $query ?? null,
            'products' => []
        ]);
    }
}