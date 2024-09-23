<?php

namespace Webkul\Shop\DataGrids;

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

        $queryBuilder = DB::table('orders as order')
            ->addSelect('order.id', 'order_items.name', 'order.increment_id', 'order.status', 'order.created_at', 'order.grand_total',
                'order.order_currency_code')
            ->leftJoin('order_items', 'order_items.order_id', '=', 'order.id')
            ->groupBy('order.id')
            ->where('customer_id', auth()->guard('customer')->user()->id);

        $this->addFilter('status', 'order.status');

        $this->setQueryBuilder($queryBuilder);

    }

    /**
     * Prepare view data.
     *
     * @return array
     */
    public function prepareViewData()
    {
        return [
            'index'             => $this->index,
            'records'           => $this->getCollection(),
            'columns'           => $this->completeColumnDetails,
            'actions'           => $this->actions,
            'enableActions'     => $this->enableAction,
            'massactions'       => $this->massActions,
            'enableMassActions' => $this->enableMassAction,
            'paginated'         => $this->paginate,
            'itemsPerPage'      => $this->itemsPerPage,
            'norecords'         => __('ui::app.datagrid.no-records'),
            'extraFilters'      => array_merge($this->getNecessaryExtraFilters(), [
                'translation' => collect([
                    'processing'      => trans('shop::app.customer.account.order.index.processing'),
                    'completed'       => trans('shop::app.customer.account.order.index.completed'),
                    'canceled'        => trans('shop::app.customer.account.order.index.canceled'),
                    'payment_canceled'        => trans('shop::app.customer.account.order.index.paymeny_canceled'),
                    'closed'          => trans('shop::app.customer.account.order.index.closed'),
                    'pending'         => trans('shop::app.customer.account.order.index.pending'),
                    'pending_payment' => trans('shop::app.customer.account.order.index.pending-payment'),
                    'fraud'           => trans('shop::app.customer.account.order.index.fraud'),
                ])
            ]),
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
            'label'      => trans('shop::app.customer.account.order.index.order_id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('shop::app.customer.account.order.index.product_name'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customer.account.order.view.order-date'),
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)
                    ->jdate();
            }
        ]);

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('shop::app.customer.account.order.index.total'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                return core()->formatPrice($value->grand_total, $value->order_currency_code);
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('shop::app.customer.account.order.index.status'),
            'type'       => 'select',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'options'    => [
                'processing'      => trans('shop::app.customer.account.order.index.processing'),
                'completed'       => trans('shop::app.customer.account.order.index.completed'),
                'canceled'        => trans('shop::app.customer.account.order.index.canceled'),
                'closed'          => trans('shop::app.customer.account.order.index.closed'),
                'payment_canceled'        => trans('shop::app.customer.account.order.index.paymeny_canceled'),
                'pending'         => trans('shop::app.customer.account.order.index.pending'),
                'pending_payment' => trans('shop::app.customer.account.order.index.pending-payment'),
                'fraud'           => trans('shop::app.customer.account.order.index.fraud'),
            ],
            'closure'    => function ($value) {
                if ($value->status == 'processing') {
                    return '<span class="badge badge-md badge-success">'
                        .trans('shop::app.customer.account.order.index.processing').'</span>';
                } elseif ($value->status == 'completed') {
                    return '<span class="badge badge-md badge-success">'
                        .trans('shop::app.customer.account.order.index.completed').'</span>';
                } elseif ($value->status == 'canceled') {
                    return '<span class="badge badge-md badge-danger">'
                        .trans('shop::app.customer.account.order.index.canceled').'</span>';
                } elseif ($value->status == 'payment_canceled') {
                    return '<span class="badge badge-md badge-danger">'
                        .trans('shop::app.customer.account.order.index.paymeny_canceled').'</span>';
                } elseif ($value->status == 'closed') {
                    return '<span class="badge badge-md badge-info">'
                        .trans('shop::app.customer.account.order.index.closed').'</span>';
                } elseif ($value->status == 'pending') {
                    return '<span class="badge badge-md badge-warning">'
                        .trans('shop::app.customer.account.order.index.pending').'</span>';
                } elseif ($value->status == 'pending_payment') {
                    return '<span class="badge badge-md badge-warning">'
                        .trans('shop::app.customer.account.order.index.pending-payment').'</span>';
                } elseif ($value->status == 'fraud') {
                    return '<span class="badge badge-md badge-danger">'
                        .trans('shop::app.customer.account.order.index.fraud').'</span>';
                }
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('ui::app.datagrid.view'),
            'type'   => 'View',
            'method' => 'GET',
            'route'  => 'customer.orders.view',
            'icon'   => 'icon eye-icon',
        ], true);
    }
}
