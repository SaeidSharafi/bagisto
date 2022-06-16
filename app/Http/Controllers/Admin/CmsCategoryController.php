<?php

namespace App\Http\Controllers\Admin;

use App\DataGrids\CMSCategoryDataGrid;
use App\Models\CMS\CmsCategories;
use Webkul\CMS\Repositories\CmsRepository;

class CmsCategoryController extends \App\Http\Controllers\Controller
{

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CMS\Repositories\CmsRepository  $cmsRepository
     * @return void
     */
    public function __construct(CmsRepository $cmsRepository)
    {
        $this->middleware('admin');

        $this->cmsRepository = $cmsRepository;

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
            return app(CMSCategoryDataGrid::class)->toJson();
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
            'slug'      => ['required', 'unique:cms_categories,slug', new \Webkul\Core\Contracts\Validations\Slug],
            'name'   => 'required',
        ]);

        $page = CmsCategories::create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'page']));

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
        $category = CmsCategories::query()->findOrFail($id);

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
            'slug'      => ['required', 'unique:cms_categories,slug,'.$id, new \Webkul\Core\Contracts\Validations\Slug],
            'name'   => 'required',
        ]);

        $category = CmsCategories::query()->find($id);
        $category->update(request()->all());
        //CmsCategories::update(request()->all(), ['id'=>$id]);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'دسته بندی']));

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
        $category = CmsCategories::query()->find($id);

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
                $category = CmsCategories::query()->find($categoryId);

                if ($category) {
                    $category->delete();

                    $count++;
                }
            }

            if (count($categoryIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => 'CMS Categories',
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => 'CMS Categories',
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.cms.category.index');
    }
}