<?php

namespace Encore\TreeTable;

use Encore\Admin\Extension;

class TreeTableExtension extends Extension
{
    public $name = 'tree-table';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Treetable',
        'path'  => 'tree-table',
        'icon'  => 'fa-gears',
    ];
}