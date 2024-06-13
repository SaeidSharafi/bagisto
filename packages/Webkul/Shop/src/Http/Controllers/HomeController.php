<?php

namespace Webkul\Shop\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\SearchRepository;

class HomeController extends Controller
{
    /**
     * Slider repository instance.
     *
     * @var \Webkul\Core\Repositories\SliderRepository
     */
    protected $sliderRepository;

    /**
     * Search repository instance.
     *
     * @var \Webkul\Core\Repositories\SearchRepository
     */
    protected $searchRepository;

    /**
     * Product repository instance.
     *
     * @var \Webkul\Core\Repositories\ProductFlatRepository
     */
    protected $productFlatRepository;

    /**
     * Category repository instance.
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository  $sliderRepository
     * @param  \Webkul\Product\Repositories\SearchRepository  $searchRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     *
     * @return void
     */
    public function __construct(
        SliderRepository $sliderRepository,
        SearchRepository $searchRepository,
        ProductFlatRepository $productFlatRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->sliderRepository = $sliderRepository;

        $this->searchRepository = $searchRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }

    /**
     * Loads the home page for the storefront.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sliderData = $this->sliderRepository->getActiveSliders();
        $velocity = app('Webkul\Velocity\Helpers\Helper')->getVelocityMetaData();
        $special_id = app('Webkul\Velocity\Helpers\Helper')->getVelocityMetaData()->special_id;
        $special_product = null;
        info('Special id:'.$special_id);
        if ($special_id) {
            $special_product = $this->productFlatRepository->findOneWhere(['sku' => $velocity->special_id]);
            if ($special_product) {
                $special_product = [
                    'short_name' => $special_product->short_name,
                    'special_price_to' => $velocity->special_to,
                    'url_key' => $special_product->url_key,
                    'specialOfferTimeLeft' => $velocity->special_to
                    ? Carbon::parse($velocity->special_to)->diff(Carbon::now())->format('%d:%H:%I:%S')
                    : '00:00:00:00',
                ];
            }

        } else {
            $special_product = $this->productFlatRepository->findOneWhere(['featured' => 1]);
        }
        $carousels = $this->categoryRepository->withCount('products')->findWhere(['is_carousel' => 1])->filter(function ($item) {
            return $item->products_count != 0;
        })->map(function ($item) {

            $item->image = $item->image ? Storage::url($item->image)  : '/images/category-base.png';
            return $item;
        });
        return view($this->_config['view'], compact('sliderData', 'special_product','carousels'));
    }

    /**
     * Loads the home page for the storefront if something wrong.
     *
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    /**
     * Upload image for product search with machine learning.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        return $this->searchRepository->uploadSearchImage(request()->all());
    }
}
