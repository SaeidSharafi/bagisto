<?php

namespace App\Http\Controllers\Admin;

use App\DataGrids\ContactRequestDataGrid;
use App\Http\Controllers\Controller;
use App\Models\Admin\ContactRequest;

class ContactRequestController extends Controller
{
    protected $_config;

    public function __construct()
    {
        $this->middleware('admin');

        $this->_config = request('_config');
    }


    public function index()
    {
        if (request()->ajax()) {
            return app(ContactRequestDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function view($id)
    {
        $contactRequest = ContactRequest::findOrFail($id);
        return view($this->_config['view'],compact('contactRequest'));
    }
    public function delete($id)
    {
        $center = ContactRequest::query()->findOrFail($id);

        if ($center->delete()) {
            return response()->json(['message' => trans('admin.contact_request.delete-success')]);
        }

        return response()->json(['message' => trans('admin.contact_request.delete-failure')], 500);
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
                $center = ContactRequest::query()->find($centerId);

                if ($center) {
                    $center->delete();

                    $count++;
                }
            }

            if (count($centerIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => __('admin.contact_request.titles'),
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => __('admin.contact_request.titles'),
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.contact-request.index');
    }
}
