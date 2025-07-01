<?php

namespace Webkul\Admin\Http\Controllers;

use Excel;
use Webkul\Admin\Exports\DataGridExport;

class ExportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Export datagrid.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $criteria = request()->all();

        $format = $criteria['format'];

        $gridName = explode('\\', $criteria['gridName']);

        $path = '\Webkul\Admin\DataGrids' . '\\' . last($gridName);

        $gridInstance = app($path);

        $records = $gridInstance->export();

        if (auth()->guard('admin')->user()?->role_id !== 1  && auth()->guard('admin')->user()?->role_id !== 2) {
            session()->flash('warning', 'دسترسی شما برای صادر کردن اطلاعات کافی نیست');

            return redirect()->back();
        }

        if (! count($records)) {
            session()->flash('warning', trans('admin::app.export.no-records'));

            return redirect()->back();
        }

        if ($format == 'csv') {
            return Excel::download(new DataGridExport($records), last($gridName) . '.csv');
        }

        if ($format == 'xls') {
            return Excel::download(new DataGridExport($records), last($gridName) . '.xlsx');
        }

        session()->flash('warning', trans('admin::app.export.illegal-format'));

        return redirect()->back();
    }
}
