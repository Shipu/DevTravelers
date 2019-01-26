@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::logmanager.log_manager') }}<small>{{ trans('backpack::logmanager.log_manager_description') }}</small>
      </h1>
      <ol class="breadcrumb">
      <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
      <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}">{{ trans('backpack::logmanager.log_manager') }}</a></li>
      <li class="active">{{ trans('backpack::logmanager.preview') }}</li>
      </ol>
    </section>
@endsection

@section('content')

  <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::logmanager.back_to_all_logs') }}</a><br><br>
<!-- Default box -->
  <div class="box">
    <div class="box-body">
      <h3>{{ $file_name }}:</h3>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          @forelse($logs as $key => $log)
            <div class="panel panel-{{ $log['level_class'] }}">
              <div class="panel-heading" role="tab" id="heading{{ $key }}">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                    <i class="fa fa-{{ $log['level_img'] }}"></i>
                    <span>[{{ $log['date'] }}]</span>
                    {{ str_limit($log['text'], 150) }}
                  </a>
                </h4>
              </div>
              <div id="collapse{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $key }}">
                <div class="panel-body">
                  <p>{{$log['text']}}</p>
                  <pre>
                    {{ trim($log['stack']) }}
                  </pre>
                </div>
              </div>
            </div>
          @empty
            <h3 class="text-center">No Logs to display.</h3>
          @endforelse
        </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/styles/default.min.css">
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js"></script>
  <script>hljs.initHighlightingOnLoad();</script>
@endsection
