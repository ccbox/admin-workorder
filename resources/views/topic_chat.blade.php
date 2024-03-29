<!-- Conversations are loaded here -->
<div class="direct-chat-messages">

  @if($current_user_id != $topic->user_id)
    <!-- ******** Message. Default to the left -->
    <div class="direct-chat-msg topic">
  @else
    <!-- ******** Message to the right -->
    <div class="direct-chat-msg topic right">
  @endif
      <!-- /.direct-chat-info -->
      <img class="direct-chat-img" src="{{ $topic->user->avatar }}" alt="message user image">
      <!-- /.direct-chat-img -->
      <div class="direct-chat-text">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name pull-left" title="{{ $topic->user_id }}">{{ $topic->user->name }}</span>
          &nbsp;&nbsp;&nbsp;
          <span class="direct-chat-timestamp">{{ $topic->created_at }} &nbsp;&nbsp;</span>
          <a class="direct-chat-timestamp pull-right" href="{{admin_url('admin-workorder/tickets').'?topic_id='.$topic->id}}">回复</a>
          <span class="direct-chat-timestamp pull-right">话题ID:{{ $topic->id }} &nbsp;&nbsp;</span>
        </div>
        <div class="title">[ {{$topic->title}} ]</div>
        {!! $topic->content !!}
        @if($topic->images)
          <div class="row">
            @foreach($topic->images_url as $image)
              <div class="col-md-4">
                <a href="{{$image}}" target="_blank" rel="noopener noreferrer">
                  <img src="{{$image}}" alt="" class="img img-thumbnail">
                </a>
              </div>
            @endforeach
          </div>
        @endif
      </div>
      <!-- /.direct-chat-text -->
    </div>
    <!-- /.direct-chat-msg -->
  
  @foreach($replies as $reply)
    @if($current_user_id != $reply->user_id)
      <!-- ******** Message. Default to the left -->
      <div class="direct-chat-msg">
    @else
      <!-- ******** Message to the right -->
      <div class="direct-chat-msg right">
    @endif
        <!-- /.direct-chat-info -->
        <img class="direct-chat-img" src="{{ $reply->user->avatar }}" alt="message user image">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name" title="{{ $reply->user_id }}">{{ $reply->user->name }}</span>
            &nbsp;&nbsp;&nbsp;
            <span class="direct-chat-timestamp">{{ $reply->created_at }}</span>
            &nbsp;&nbsp;&nbsp;
            @if($reply->type!='reply')
              <span class="direct-chat-timestamp badge bg-red">{{ $actions[$reply->type] }}工单</span>
            @else
              <span class="direct-chat-timestamp">{{ $actions[$reply->type] }}工单</span>
            @endif

            <a class="direct-chat-timestamp pull-right" href="{{admin_url('admin-workorder/tickets').'?topic_id='.$reply->topic_id .'&parent_id='.$reply->id}}">回复</a>
            <span class="direct-chat-timestamp pull-right">话题ID:{{ $reply->id }} &nbsp;&nbsp;</span>
            <!-- <span class="direct-chat-timestamp pull-right">话题PID:{{ $reply->parent_id }}&nbsp;&nbsp;</span> -->
          </div>
          
          @if($reply->parent_id != $reply->topic_id)
            <div class="callout bg-gray" style="margin:0px;padding:5px;">
              <div style="padding-bottom:5px;">
                <img class="direct-chat-img" style="float:left;margin:0 5px;" src="{{ $reply->reparent->user->avatar }}" alt="message user image">
                {{ $reply->reparent->user->name }}
                &nbsp;&nbsp;&nbsp;
                {{ $reply->reparent->created_at }}
                &nbsp;&nbsp;&nbsp;
                <br>
                (话题ID：{{ $reply->reparent->id }})
              </div>
              <div>
                {!! $reply->reparent->content !!}
              </div>
            </div>
          @endif

          {!! $reply->content !!}
          @if($reply->images)
            <div class="row">
              @foreach($reply->images_url as $image)
                <div class="col-md-4">
                  <a href="{{$image}}" target="_blank" rel="noopener noreferrer">
                    <img src="{{$image}}" alt="" class="img img-thumbnail">
                  </a>
                </div>
              @endforeach
            </div>
          @endif
        </div>
        <!-- /.direct-chat-text -->
      </div>
      <!-- /.direct-chat-msg -->
  @endforeach

</div>
<!--/.direct-chat-messages-->


<style>
.direct-chat-messages {
  /* -webkit-transform: translate(0, 0); */
  /* -ms-transform: translate(0, 0); */
  /* -o-transform: translate(0, 0); */
  /* transform: translate(0, 0); */
  /* padding: 10px; */
  height: auto;
  /* overflow: auto; */
}
.right .direct-chat-timestamp {
    color: #f2f2f2;
}
.direct-chat-info {
    /* display: block; */
    /* margin-bottom: 2px; */
    /* font-size: 12px; */
    height: 18px;
}
.direct-chat-text {
    /* border-radius: 5px; */
    /* position: relative; */
    /* padding: 5px 10px; */
    /* background: #d2d6de; */
    border: none;
    margin: 5px 50px 0 50px;
    /* color: #444; */
}
.direct-chat-text .title{
  font-size:16px;
}
.direct-chat-text .img-thumbnail{
  /* max-width:200px; */
  /* max-height:200px; */
  background: none;
  border-radius:0px;
  padding:1px;
  margin-bottom: 5px;
}
.direct-chat-danger .right>.direct-chat-text {
    background: #38ac77;
    /* border-color: #dd4b39; */
    color: #fff;
    /* margin-right: 50px; */
    margin-left: 50px;
}
.direct-chat-danger .right>.direct-chat-text:after,
.direct-chat-danger .right>.direct-chat-text:before {
    border-left-color: #38ac77;
}
.direct-chat-danger .topic>.direct-chat-text {
    background: #d66;
    color:#fff;
}
.topic .direct-chat-timestamp {
    color: #f2f2f2;
}
.direct-chat-danger .topic>.direct-chat-text:after,
.direct-chat-danger .topic>.direct-chat-text:before {
    border-right-color: #d66;
}
.direct-chat-danger .right>.direct-chat-text:before{
  border-left-color:#fff;
  border-right-color:#fff;
}
.direct-chat-danger .topic.right>.direct-chat-text:after{
  border-left-color:#d66;
  border-right-color:#fff;
}
</style>