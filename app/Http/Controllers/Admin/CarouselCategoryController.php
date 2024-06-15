<?php

namespace App\Http\Controllers\Admin;

use App\DataGrids\CarouselCategoryDataGrid;
use App\Models\Admin\CarouselCategory;
class CarouselCategoryController extends \App\Http\Controllers\Controller
{


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
            return app(CarouselCategoryDataGrid::class)->toJson();
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
        return view($this->_config['view']);
    }

    /**
     * To store a new CMS page in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        $this->validate(request(), [
            'title'   => 'required',
            'order'   => 'required',
        ]);

        $page = CarouselCategory::create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => __('admin.carousel.category.title')]));

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
        $category = CarouselCategory::query()->findOrFail($id);

        return view($this->_config['view'], compact('category'));
    }

    /**
     * To update the previously created CMS category in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        $this->validate(request(), [
            'title'   => 'required',
            'order'   => 'required',
        ]);

        $category = CarouselCategory::query()->find($id);
        $category->update(request()->all());
        //CmsCategories::update(request()->all(), ['id'=>$id]);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => __('admin.carousel.category.title')]));

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
        $category = CarouselCategory::query()->find($id);

        if ($category->delete()) {
            return response()->json(['message' => trans('admin::app.cms.pages.delete-success')]);
        }

        return response()->json(['message' => trans('admin::app.cms.pages.delete-failure')], 500);
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
            $categoryIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($categoryIDs as $categoryId) {
                $category = CarouselCategory::query()->find($categoryId);

                if ($category) {
                    $category->delete();

                    $count++;
                }
            }

            if (count($categoryIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => __('admin.carousel.category.titles'),
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => __('admin.carousel.category.titles'),
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.carousel.category.index');
    }
}