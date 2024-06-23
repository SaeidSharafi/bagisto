<?php

namespace App\Http\Controllers\Admin;

use App\DataGrids\CenterDataGrid;
use App\Http\Requests\Admin\CenterRequest;
use App\Models\Admin\Center;
use Webkul\CMS\Http\Controllers\Controller;

class CenterController extends Controller
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
            return app(CenterDataGrid::class)->toJson();
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
        $categories = Center::all();
        return view($this->_config['view'],compact('categories'));
    }

    /**
     * To store a new CMS page in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CenterRequest $request)
    {
        $data = $request->validated();

        Center::create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => __('admin.center.title')]));

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
        $center = Center::query()->findOrFail($id);

        return view($this->_config['view'], compact('center'));
    }

    /**
     * To update the previously created CMS page in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CenterRequest $request, $id)
    {
        $data = $request->validated();
        $center = Center::query()->findOrFail($id);


        $center->update($data);

        session()->flash('success', trans('admin::app.response.update-success', ['name' =>  __('admin.center.title')]));

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
        $center = Center::query()->findOrFail($id);

        if ($center->delete()) {
            return response()->json(['message' => trans('admin.center.delete-success')]);
        }

        return response()->json(['message' => trans('admin.center.delete-failure')], 500);
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
            $centerIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($centerIDs as $centerId) {
                $center = Center::query()->find($centerId);

                if ($center) {
                    $center->delete();

                    $count++;
                }
            }

            if (count($centerIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => __('admin.center.titles'),
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => __('admin.center.titles'),
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.center.index');
    }
}
