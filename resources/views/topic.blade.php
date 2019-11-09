<!-- Construct the box with style you want. Here we are using box-danger -->
<!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
<!-- The contextual class should match the box, so we are using direct-chat-danger -->
<div class="box box-danger direct-chat direct-chat-danger">

  <div class="box-header with-border">
    <h3 class="box-title">工单详情</h3>
    <div class="box-tools pull-right">
      <span data-toggle="tooltip" title="总回复" class="badge bg-red">{{ $topic->discusses->count()}}</span>
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <!-- In box-tools add this button if you intend to use the contacts pane -->
      <button class="btn btn-box-tool" data-toggle="tooltip" title="TOPIC" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
      <!-- <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
    </div>
  </div>
  <!-- /.box-header -->

  <div class="box-body">

    {!! $replies_html !!}

    <!-- Contacts are loaded here -->
    <div class="direct-chat-contacts">
      <ul class="contacts-list">
        <li>
          <a href="#">
            <img class="contacts-list-img" src="{{ $topic->user->avatar}}" alt="Contact Avatar">
            <div class="contacts-list-info">
              <span class="contacts-list-name">
              {{ $topic->user->name}}
                <small class="contacts-list-date pull-right">{{ $topic->created_at }}</small>
                </span>
              <span class="contacts-list-msg">{!! $topic->content !!}</span>
            </div>
            <!-- /.contacts-list-info -->
          </a>
        </li>
        <!-- End Contact Item -->
      </ul>
      <!-- /.contacts-list -->
    </div>
    <!-- /.direct-chat-pane -->

  </div>
  <!-- /.box-body -->

  <div class="box-footer">
    <!-- <div class="input-group">
      <input type="text" name="message" placeholder="Type Message ..." class="form-control">
      <span class="input-group-btn">
          <button type="button" class="btn btn-danger btn-flat">Send</button>
      </span>
    </div> -->
    {!! $form_html !!}
  </div>
  <!-- /.box-footer-->

</div>
<!--/.direct-chat -->