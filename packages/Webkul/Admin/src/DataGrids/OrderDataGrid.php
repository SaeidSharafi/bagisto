<?php

namespace Webkul\Admin\DataGrids;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class OrderDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $index = 'id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $product_ids = [];
        if (auth()->guard('admin')->user()?->role_id == config('app.teacher.role_id')) {
            $product_ids = DB::table('products')->select('id')
                ->where('teacher_id', auth()->guard('admin')->user()->id)->get()->pluck('id')->flatten()->toArray();
        }

        $queryBuilder = DB::table('orders')
            ->when($product_ids, function ($query) use ($product_ids) {
                $query->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                    ->whereIn('order_items.product_id', $product_ids);
            })->when(!$product_ids, fn($q) => $q->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id'))
            ->groupBy('orders.id')
            ->addSelect('orders.id', 'orders.customer_phone', 'orders.increment_id', 'orders.base_sub_total',
                'orders.base_grand_total', 'orders.created_at', 'order_items.name', 'channel_name', 'orders.status')
            ->addSelect(DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix()
                .'orders.customer_last_name) as billed_to'));
        $this->addFilter('billed_to',
            DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix()
                .'orders.customer_last_name)'));
        $this->addFilter('increment_id', 'orders.increment_id');
        $this->addFilter('created_at', 'orders.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Prepare data for json response.
     *
     * @return array
     */
    public function prepareData()
    {
        return [
            'index'             => $this->index,
            'records'           => $this->collection,
            'columns'           => $this->completeColumnDetails,
            'actions'           => $this->actions,
            'enableActions'     => $this->enableAction,
            'massActions'       => $this->massActions,
            'enableMassActions' => $this->enableMassAction,
            'paginated'         => $this->paginate,
            'itemsPerPage'      => $this->itemsPerPage,
            'extraFilters'      => array_merge($this->getExtraFilters(), [
                'status' => [
                    'processing'      => trans('shop::app.customer.account.order.index.processing'),
                    'completed'       => trans('shop::app.customer.account.order.index.completed'),
                    'canceled'        => trans('shop::app.customer.account.order.index.canceled'),
                    'closed'          => trans('shop::app.customer.account.order.index.closed'),
                    'pending'         => trans('shop::app.customer.account.order.index.pending'),
                    'pending_payment' => trans('shop::app.customer.account.order.index.pending-payment'),
                    'fraud'           => trans('shop::app.customer.account.order.index.fraud'),
                ]
            ]),
            'translations'      => $this->getTranslations(),

        ];
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'increment_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'base_sub_total',
            'label'      => trans('admin::app.datagrid.sub-total'),
            'type'       => 'price',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.datagrid.grand-total'),
            'type'       => 'price',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.order-date'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($value) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)
                    ->jdate();
            }
        ]);
        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'select',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status == 'processing') {
                    return '<span class="badge badge-md badge-success">'
                        .trans('admin::app.sales.orders.order-status-processing').'</span>';
                } elseif ($value->status == 'completed') {
                    return '<span class="badge badge-md badge-success">'
                        .trans('admin::app.sales.orders.order-status-success').'</span>';
                } elseif ($value->status == 'canceled') {
                    return '<span class="badge badge-md badge-danger">'
                        .trans('admin::app.sales.orders.order-status-canceled').'</span>';
                } elseif ($value->status == 'closed') {
                    return '<span class="badge badge-md badge-info">'
                        .trans('admin::app.sales.orders.order-status-closed').'</span>';
                } elseif ($value->status == 'pending') {
                    return '<span class="badge badge-md badge-warning">'
                        .trans('admin::app.sales.orders.order-status-pending').'</span>';
                } elseif ($value->status == 'pending_payment') {
                    return '<span class="badge badge-md badge-warning">'
                        .trans('admin::app.sales.orders.order-status-pending-payment').'</span>';
                } elseif ($value->status == 'fraud') {
                    return '<span class="badge badge-md badge-danger">'
                        .trans('admin::app.sales.orders.order-status-fraud').'</span>';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'billed_to',
            'label'      => trans('admin::app.datagrid.billed-to'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
        if (auth()->guard('admin')->user()?->role_id != config('app.teacher.role_id')) {
            $this->addColumn([
                'index'      => 'customer_phone',
                'label'      => trans('admin::app.datagrid.phone'),
                'type'       => 'string',
                'searchable' => true,
                'sortable'   => true,
                'filterable' => true,
            ]);
        }

    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (auth()->guard('admin')->user()?->role_id != config('app.teacher.role_id')) {
            $this->addAction([
                'title'  => trans('admin::app.datagrid.view'),
                'method' => 'GET',
                'route'  => 'admin.sales.orders.view',
                'icon'   => 'icon eye-icon',
            ]);
        }

        $this->addAction([
            'title'  => trans('admin.datagrid.complete'),
            'method' => 'GET',
            'route'  => 'admin.sales.orders.complete',
            'icon'   => 'icon completed-icon'
        ]);
    }
}
