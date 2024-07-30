<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Core\Repositories\ChannelRepository;

class ChannelDataGrid extends DataGrid
{
    /**
     * Assign primary key.
     */
    protected $index = 'id';

    /**
     * Sort order.
     */
    protected $sortOrder = 'desc';

    /**
     * Filter Locale.
     */
    protected $locale;

    /**
     * ChannelRepository $channelRepository
     *
     * @var \Webkul\Core\Repositories\ChannelRepository
     */
    protected $channelRepository;

    /**
     * Create a new datagrid instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @return void
     */
    public function __construct(
        ChannelRepository $channelRepository
    )
    {
        parent::__construct();

        $this->locale = core()->getRequestedLocaleCode();

        $this->channelRepository = $channelRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = $this->channelRepository->query()
            ->leftJoin('channel_translations', function($leftJoin) {
                $leftJoin->on('channel_translations.channel_id', '=', 'channels.id')
                    ->where('channel_translations.locale', $this->locale);
            })
            ->addSelect('channels.id', 'channels.code', 'channel_translations.locale', 'channel_translations.name as translated_name', 'channels.hostname');


        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {

        $this->addColumn([
            'index'      => 'translated_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'hostname',
            'label'      => trans('admin::app.datagrid.hostname'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => false,
            'filterable' => false,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.channels.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);
    }
}