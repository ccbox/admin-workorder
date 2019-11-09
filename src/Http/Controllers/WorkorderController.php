<?php

namespace Ccbox\AdminWorkorder\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Ccbox\AdminWorkorder\Http\Controllers\TicketsController;

class WorkorderController extends Controller
{
    public function index(Content $content)
    {
        return redirect( admin_url('admin-workorder/tickets') );
        // return redirect()->action('TicketsController@index');
        // return $content
        //     ->title('工单系统首页')
        //     ->description('Description')
        //     ->body(view('admin-workorder::index'));
    }
}