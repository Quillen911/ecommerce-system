<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Services\MainService;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\MainResource;

class MainController extends Controller
{
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    protected $mainService;

    public function __construct(
        ElasticsearchService $elasticSearch,
        ElasticSearchTypeService $elasticSearchTypeService,
        ElasticSearchProductService $elasticSearchProductService,
        MainService $mainService
    ) {
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->mainService = $mainService;
    }

    public function main(Request $request)
    {
        $products   = $this->mainService->getProductsPopularVariants();
        $categories = $this->mainService->getCategories();
        $campaigns  = $this->mainService->getCampaigns();
        return new MainResource([
            'products' => $products,
            'categories' => $categories,
            'campaigns' => $campaigns
        ]);
    }

    public function filter(Request $request)
    {
        $filters = $this->elasticSearchTypeService->filterType($request);

        $data = $this->elasticSearchProductService->filterProducts(
            $filters,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        return ResponseHelper::success('Filtre Sonucu', [
            'total'    => $data['results']['total'],
            'page'     => $request->input('page', 1),
            'size'     => $request->input('size', 12),
            'filters'  => $filters,
            'products' => ProductResource::collection($data['products']),
        ]);
    }

    public function sorting(Request $request)
    {
        $sorting = $this->elasticSearchTypeService->sortingType($request);

        $data = $this->elasticSearchProductService->sortingProducts(
            $sorting,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        return ResponseHelper::success('Sıralama', [
            'total'    => $data['results']['total'],
            'page'     => $request->input('page', 1),
            'size'     => $request->input('size', 12),
            'sorting'  => $sorting,
            'products' => ProductResource::collection($data['products']),
        ]);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $data  = $this->elasticSearchProductService->autocomplete($query);

        return ResponseHelper::success('Otomatik Tamamlama', $data);
    }
}
