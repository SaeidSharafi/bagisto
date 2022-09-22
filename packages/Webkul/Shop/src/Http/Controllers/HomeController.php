<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Product\Repositories\SearchRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository  $sliderRepository
     * @param  \Webkul\Product\Repositories\SearchRepository  $searchRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @return void
     */
    public function __construct(
        protected SliderRepository $sliderRepository,
        protected SearchRepository $searchRepository,
        protected ProductFlatRepository $productFlatRepository,
    )
    {
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
        $special_product = $this->productFlatRepository->findOneWhere(['featured' => 1]);

        return view($this->_config['view'], compact('sliderData','special_product'));
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
