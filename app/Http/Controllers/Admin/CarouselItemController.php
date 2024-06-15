<?php

namespace App\Http\Controllers\Admin;

use App\DataGrids\CarouselItemDataGrid;
use App\Http\Requests\Admin\PartnerRequest;
use App\Http\Requests\Admin\PartnerUpdateRequest;
use App\Models\Admin\CarouselItem;
use App\Models\Admin\CarouselCategory;
use App\Models\CMS\CmsCategories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Admin\DataGrids\CMSPageDataGrid;
use Webkul\CMS\Http\Controllers\Controller;
use Webkul\CMS\Repositories\CmsRepository;

class CarouselItemController extends Controller
{
    /**
     * To hold the request variables from route file.
     *
     * @var array
     */
    protected $_config;

    public function __construct()
    {
        $this->middleware('admin');

        $this->_config = request('_config');
    }

    /**
     * Loads the index page showing the static pages resources.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CarouselItemDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * To create a new CMS page.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = CarouselCategory::all();
        return view($this->_config['view'],compact('categories'));
    }

    /**
     * To store a new CMS page in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image');
        unset($data['image']);
        $carouselItem = CarouselItem::create($data);

        $imagePath = $image?->store('carousel_item/'.$carouselItem->id);
        $carouselItem->image = $imagePath;
        $carouselItem->save();


        session()->flash('success', trans('admin::app.response.create-success', ['name' => __('admin.carousel.item.title')]));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To edit a previously created CMS page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $carouselItem =CarouselItem::query()->findOrFail($id);
        $categories = CarouselCategory::all();

        return view($this->_config['view'], compact('carouselItem','categories'));
    }

    /**
     * To update the previously created CMS page in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $carouselItem = CarouselItem::query()->findOrFail($id);
        $image = $request->file('image');
        unset($data['image']);
        if ($image){
            $image = $request->file('image');
            Storage::disk('public')->delete($carouselItem->image);
            $data['image'] = $image?->store('carousel_item/'.$carouselItem->id);
        }

        if (isset($data['image_delete']) && $data['image_delete']){
            $data['image'] = null;
            Storage::disk('public')->deleteDirectory($carouselItem->image);
        }


        $carouselItem->update($data);

        session()->flash('success', trans('admin::app.response.update-success', ['name' =>  __('admin.carousel.item.title')]));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To delete the previously create CMS page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $carouselItem = CarouselItem::query()->findOrFail($id);

        if ($carouselItem->delete()) {
            return response()->json(['message' => trans('admin.carousel.item.delete-success')]);
        }

        return response()->json(['message' => trans('admin.carousel.item.delete-failure')], 500);
    }

    /**
     * To mass delete the CMS resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete()
    {
        $data = request()->all();

        if ($data['indexes']) {
            $carouselItemIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($carouselItemIDs as $carouselItemId) {
                $carouselItem = CarouselItem::query()->find($carouselItemId);

                if ($carouselItem) {
                    $carouselItem->delete();

                    $count++;
                }
            }

            if (count($carouselItemIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => __('admin.carousel.item.titles'),
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => __('admin.carousel.item.titles'),
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.carousel.item.index');
    }
}
