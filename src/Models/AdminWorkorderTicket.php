<?php

namespace Ccbox\AdminWorkorder\Models;

use Illuminate\Database\Eloquent\Model;

class AdminWorkorderTicket extends Model
{
    // protected $guarded = [];

    protected $appends = [
        'images_url', 
    ];
    
    const TYPE_DEFAULT      = 'consult';
    const TYPE_CONSULT      = 'consult';
    const TYPE_ISSUE        = 'issue';
    const TYPE_BUG          = 'bug';
    const TYPE_IMPROVE      = 'improve';
    const TYPE_OTHER        = 'other';

    const TYPE_REPLY        = 'reply';
    
    public static $typeMap = [
        self::TYPE_CONSULT  => '咨询',
        self::TYPE_ISSUE    => '问题',
        self::TYPE_BUG      => 'BUG',
        self::TYPE_IMPROVE  => '优化',
        self::TYPE_OTHER    => '其他',
    ];

    public static $levelMap = [
        1=>'A级-马上处理',
        2=>'B级-尽快处理',
        3=>'C级-等待处理',
        4=>'D级-需要处理',
        5=>'E级-适当排期'
    ];

    const REPLY_TYPE    = 'chat';

    const REPLY_FORUM   = 'forum';
    const REPLY_CHAT    = 'chat';

    public static $replyMap = [
        self::REPLY_FORUM   => '论坛模式',
        self::REPLY_CHAT    => '对话模式',
    ];

    protected $casts = [
        'images' => 'json',
        'closed' => 'boolean',
    ];

    public function user(){
        $userModel = config('admin.database.users_model');
        return $this->belongsTo($userModel);
    }

    public function discusses()
    {
        return $this->hasMany(AdminWorkorderTicket::class, 'topic_id')->orderBy('id');
    }

    public function replies()
    {
        return $this->hasMany(AdminWorkorderTicket::class, 'parent_id')->orderBy('id');
    }
    
    // get: images_url
    public function getImagesUrlAttribute()
    {
        $return = [];
        if(!empty($this->images)){
            foreach($this->images as $val){
                $return[] = image_full_src($val);
            }
        }
        return $return;
    }
}
