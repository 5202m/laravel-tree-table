<?php

namespace Encore\TreeTable;

use Encore\Admin\Admin;
use Illuminate\Support\ServiceProvider;

class TreeTableServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(TreeTableExtension $extension)
    {
        if (! TreeTableExtension::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'tree-table');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/tree-table')],
                'tree-table'
            );
        }

        Admin::booting(function(){
            Admin::css('vendor/laravel-admin-ext/tree-table/css/screen.css');
            Admin::css('vendor/laravel-admin-ext/tree-table/css/jquery.treetable.css');
            Admin::css('vendor/laravel-admin-ext/tree-table/css/jquery.treetable.theme.default.css');
            Admin::js('vendor/laravel-admin-ext/tree-table/jquery.treetable.js');
        });
    }
}