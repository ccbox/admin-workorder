<?php

namespace Ccbox\AdminWorkorder\Http\Controllers;

use Ccbox\AdminWorkorder\Models\AdminWorkorderTicket;

use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Form as wForm;

use Encore\Admin\Facades\Admin;

class TicketsController extends AdminController
{
    protected $title = '工单管理';
    
    protected $topic_id = 0;
    protected $topic_data = null;
    protected $parent_id = 0;
    protected $parent_data = null;

    protected $topic_view = "";
    protected $form_title = '创建工单';
    
    public function index(Content $content)
    {
        $this->topic_id =  request()->input('topic_id', 0);
        if ($this->topic_id>0) {
            $topic_id = AdminWorkorderTicket::getTopicId($this->topic_id);
            if($topic_id != $this->topic_id){
                // $this->topic_id = $topic_id;
                return redirect( admin_url('admin-workorder/tickets') . '?topic_id='.$topic_id );
            }
        }
        $this->parent_id = request()->input('parent_id', $this->topic_id);

        if($this->topic_id>0){
            $this->topic_id = AdminWorkorderTicket::getTopicId($this->topic_id);
            if(AdminWorkorderTicket::REPLY_TYPE == AdminWorkorderTicket::REPLY_CHAT){
                // $this->parent_id = $this->topic_id;
                $topic_data = AdminWorkorderTicket::query()
                                ->with(['discusses'=> function ($query) {
                                    $query->orderBy('id', 'asc');
                                }])
                                ->find($this->topic_id);
            }else{
                $topic_data = AdminWorkorderTicket::query()
                                ->with(['replies'=> function ($query) {
                                    $query->orderBy('id', 'asc');
                                }])
                                ->find($this->topic_id);
            }

            if($topic_data){
                $this->topic_data = $topic_data;

                if($this->parent_id != $this->topic_id){
                    $this->parent_data = AdminWorkorderTicket::query()->find($this->parent_id);
                }else{
                    $this->parent_data = $topic_data;
                }

                $this->topic_view = $this->topicView($topic_data);
            }
        }
        
        $form_view = $this->form();

        return $content
            ->title($this->title)
            ->description($this->title)
            ->row(function (Row $row) use ($form_view){
                $row->column(6, $this->grid());
                $row->column(6, function (Column $column) use ($form_view){
                    if($this->topic_view){
                        $column->row($this->topic_view);
                    }else{
                        $column->row($form_view);
                    }
                });
            });
    }
    
    protected function topicView($topic)
    {
            $replies_html = $this->topicReplyView($topic);
            
            $form_html = '';
            $this->form_title = '回复：'.$this->parent_data->user->name.'（话题ID：'.$this->parent_data->id.'）';
            $form_html = $this->form()->render();

            $view = view('admin-workorder::topic', [
                'topic'             => $topic,
                'current_user_id'   => Admin::user()->id,
                'replies_html'      => $replies_html,
                'form_html'         => $form_html,
            ])->render();
            return $view;
    }

    protected function topicReplyView($topic, $level=0)
    {
        $html = '';
        // if(AdminWorkorderTicket::REPLY_TYPE == AdminWorkorderTicket::REPLY_CHAT){
            $view_tpl = 'admin-workorder::topic_chat';
            $html = view($view_tpl, [
                'topic'             => $topic,
                'replies'           => $topic->discusses,
                'actions'           => AdminWorkorderTicket::$actionMap,
                'current_user_id'   => Admin::user()->id,
                'level'             => $level,
            ])->render();
            return $html;
        // }else{
        //     $view_tpl = 'admin-workorder::topic_forum';
        // }
    }

    protected function grid()
    {
        $grid = new Grid(new AdminWorkorderTicket());

        $grid->model()->with('discusses')->where(function($query){
            $query->where('topic_id', 0)->orWhere('topic_id', null);
        })->orderBy('id','desc');
        
        if(request('closed')==null){
            $grid->model()->where('closed', 0);
        }
        if(request('closed')=='all'){
            request()->offsetSet('closed', null);
        }

        $grid->id('ID')->sortable();
        
        $grid->column('type', '类型')->using(AdminWorkorderTicket::$typeMap)->filter(AdminWorkorderTicket::$typeMap)->sortable();
        $grid->column('level', '等级')->using(AdminWorkorderTicket::$levelMap)->filter(AdminWorkorderTicket::$levelMap)->sortable();
        $grid->column('discusses_count','回复数')->display(function($value){
            return $this->discusses->count();
        });
        // $grid->user_id('用户ID');
        $grid->user()->name('发起人');
        $grid->column('title', '标题');
        // $grid->column('content', '内容')->limit(50);
        $grid->closed('状态')->display(function($value) {
            if($this->closed){
                return '<spane class="badge bg-gray">已关闭</span>';
            }elseif($this->closed_at){
                return '<spane class="badge bg-red">已重启</span>';
            }
            return '<spane class="badge bg-orange">未结束</span>';
        });

        $grid->column('created_at', '创建时间')->sortable();
        $grid->column('updated_at', '更新时间')->sortable();
        
        // 不在每一行后面展示的按钮
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
            $actions->disableEdit();
            
            $view_url = admin_url('admin-workorder/tickets', ['topic_id'=>$actions->row->id]); // 跳到详情页，不过新添、列表等按钮要处理去掉上一层
            // $view_url = admin_url('admin-workorder/tickets') . '?topic_id='.$actions->row->id;  // 跳到首页统一处理
            $actions->append('<a href="'.$view_url.'"><i class="fa fa-eye"></i></a>');
        });

        $grid->filter(function ($filter) {
            $filter->between('created_at','创建时间')->datetime();
            $filter->equal('closed', '状态')->radio(['all'=>'全部','1'=>'关闭', '0'=>'激活'])->default('0');
            // $filter->in('type', '类型')->checkbox(AdminWorkorderTicket::$typeMap);
            // $filter->in('level', '等级')->checkbox(AdminWorkorderTicket::$levelMap);
            $userModel = config('admin.database.users_model');
            // $filter->equal('user_id', '发起人')->select($userModel::all()->pluck('name', 'id'));
            $filter->where(function ($query) {
                $id = $this->input;
                if(is_numeric($id)){
                    $query->where('id', $id);
                }else{
                    $query->where('title', 'like', "%$id%");
                    $query->orWhere('content', 'like', "%$id%");
                }
            }, '模糊搜索')->placeholder('请输入关键字进行搜索');
        });
        
        return $grid;
    }
    
    protected function form()
    {
        $form = new Form(new AdminWorkorderTicket());

        $title = $this->form_title;
        $form->setTitle($title);
        $form->setAction(admin_base_path('admin-workorder/tickets'));
        
        if($this->topic_id > 0){
            // Builder::PREVIOUS_URL_KEY = '_previous_';
            request()->offsetSet('_previous_', admin_url('admin-workorder/tickets', ['topic_id'=>$this->topic_id, 'from'=>'form']));
            request()->offsetSet('after-save', 5);
        }else{
            request()->offsetSet('after-save', 3);
        }
        
        if($this->topic_id < 1){
            $form->text('title', '标题')->rules('required');
            $form->radio('type','类型')
                ->options(AdminWorkorderTicket::$typeMap)
                ->rules('required')
                ->default(AdminWorkorderTicket::TYPE_DEFAULT);
            $form->radio('level','等级')->options(AdminWorkorderTicket::$levelMap)->default(3);
        }else{
            $form->hidden('title', '标题')->value('Re:'.$this->topic_id.'-'.$this->parent_id)->rules('required');
            // $form->hidden('type','类型')->rules('required')->value('reply');
            $actionMap = AdminWorkorderTicket::$actionMap;
            if($this->topic_data->closed){
                unset($actionMap['close']);
            }else{
                unset($actionMap['reopen']);
            }
            $form->radio('type','操作')
                ->options($actionMap)
                ->rules('required')
                ->default('reply');
            $form->hidden('level','等级')->rules('required')->value($this->topic_data->level);
            if($this->topic_id != $this->parent_id){
                $html = '';
                $html .= '<div class="callout bg-gray" style="margin:5px 0;padding:5px;">';
                $html .= '<div style="padding-bottom:5px;">';
                $html .= '<img class="direct-chat-img" style="float:left;margin:0 5px;" src="'.$this->parent_data->user->avatar.'" alt="message user image">';
                $html .= $this->parent_data->user->name;
                $html .= '&nbsp;&nbsp;&nbsp;';
                $html .= $this->parent_data->created_at;
                $html .= '&nbsp;&nbsp;&nbsp;';
                $html .= '<br>';
                $html .= '(话题ID：'.$this->parent_data->id.')';
                $html .= '</div>';
                $html .= '<div>'.$this->parent_data->content.'</div>';
                $html .= '</div>';
                $form->html($html);
            }
        }
        // $form->textarea('content','内容')->rules('required')->rows(2);
        // $form->text('content','内容')->rules('required');
        $form->editor('content','内容')->rules('required')->options([
            'menus'=>[
                'head',         // 标题
                'bold',         // 粗体
                'fontSize',     // 字号
                // 'fontName',     // 字体
                'italic',       // 斜体
                'underline',    // 下划线
                // 'strikeThrough',// 删除线
                'foreColor',    // 文字颜色
                'backColor',    // 背景颜色
                'link',         // 插入链接
                'list',         // 列表
                // 'justify',      // 对齐方式
                'quote',        // 引用
                'emoticon',     // 表情
                // 'image',        // 插入图片
                'table',        // 表格
                // 'video',        // 插入视频
                'code',         // 插入代码
                'undo',         // 撤销
                'redo'          // 重复
            ]]);
        $form->multipleImage('images', '图片')->removable()->move('workorder')->uniqueName();
        
        // $form->switch('closed','关闭工单');
        $form->hidden('closed')->default(0)->value(0);

        $form->hidden('topic_id')->default(0)->value($this->topic_id);
        $form->hidden('parent_id')->default(0)->value($this->parent_id);

        // $form->hidden('user_id')->default(\Admin::user()->id);
        $form->hidden('user_id')->default(\Admin::user()->id);
        $form->hidden('username')->default(\Admin::user()->username);
        $form->hidden('nickname')->default(\Admin::user()->name);
        $form->hidden('rolename')->default(\Admin::user()->roles->pluck('name')->implode('|'));

        $form->footer(function (Form\Footer $footer){
            $footer->disableViewCheck();
            $footer->disableCreatingCheck();
            $footer->disableEditingCheck();
        });
        $form->tools(function (Form\Tools $tools){
            $tools->disableList(false);
        });
        $form->setWidth(10, 2);

        $form->saving(function (Form $form) {
            // dd($form->input('closed'),$form->closed, $form->topic_id, $form->model()->topic);
            if ($form->topic_id>0){
                $topic = AdminWorkorderTicket::find($form->topic_id);
                if($form->type=='close'){
                    $topic->closed = true;
                    $topic->closed_at = Carbon::now();
                    $topic->closed_by = \Admin::user()->id;
                    $topic->save();
                }elseif($form->type=='reopen'){
                    $topic->closed = false;
                    // $topic->closed_at = Carbon::now();
                    // $topic->closed_by = \Admin::user()->id;
                    $topic->save();
                }
            }else{

            }
        });

        return $form;
    }

    public function show($id, Content $content)
    {
        // 跳回首页操作
        // request()->offsetSet('topic_id', $id);
        // return $this->index($content);
        return redirect( admin_url('admin-workorder/tickets') . '?topic_id='.$id );
    }

}