@extends('layouts.master')

@section('content-header')
<h1>
    {{ trans('blog::category.title.edit category') }} <small>{{ $category->name }}</small>
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ URL::route('admin.blog.category.index') }}">{{ trans('blog::category.title.category') }}</a></li>
    <li class="active">{{ trans('blog::category.title.edit category') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['admin.blog.category.update', $category->id], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-10">
        <div class="nav-tabs-custom">
            @include('partials.form-tab-headers')
            <div class="tab-content">
                <?php $i = 0; ?>
                <?php foreach (LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
                    <?php $i++; ?>
                    <div class="tab-pane {{ App::getLocale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                        @include('blog::admin.categories.partials.edit-fields', ['lang' => $locale])
                    </div>
                <?php endforeach; ?>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                    <button class="btn btn-default btn-flat" name="button" type="reset">{{ trans('core::core.button.reset') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('admin.blog.category.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                </div>
            </div>
        </div> {{-- end nav-tabs-custom --}}
    </div>
    <div class="col-md-2">
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::normalInput('ordering', trans('blog::category.form.ordering'), $errors, $category) !!}
            </div>
        </div>
        @if($currentUser->hasAccess('blog.categories.sitemap'))
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    {!! Form::hidden("meta_robot_no_index", 'index') !!}
                    {!! Form::checkbox("meta_robot_no_index", 'noindex', old("meta_robot_no_index", ($category->meta_robot_no_index == 'index' ? 0 : 1)), ['class' => 'flat-blue']) !!}
                    {!! Form::label("meta_robot_no_index", trans('blog::post.form.meta_robot_no_index')) !!}
                    {!! $errors->first("meta_robot_no_index", '<span class="help-block">:message</span>') !!}
                    <br/>
                    {!! Form::hidden("meta_robot_no_follow", 'follow') !!}
                    {!! Form::checkbox("meta_robot_no_follow", 'nofollow', old("meta_robot_no_follow", ($category->meta_robot_no_follow == 'follow' ? 0 : 1)), ['class' => 'flat-blue']) !!}
                    {!! Form::label("meta_robot_no_follow", trans('blog::post.form.meta_robot_no_follow')) !!}
                    {!! $errors->first("meta_robot_no_follow", '<span class="help-block">:message</span>') !!}
                    <br/>
                    {!! Form::checkbox("sitemap_include", '1', old("sitemap_include", $category->sitemap_include), ['class' => 'flat-blue']) !!}
                    {!! Form::label("sitemap_include", trans('core::sitemap.title.include')) !!}
                    {!! $errors->first("sitemap_include", '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                    {!! Form::normalSelect('sitemap_frequency', trans('core::sitemap.title.frequency'), $errors, $sitemapFrequencies, $category->sitemap_frequency) !!}
                </div>
                <div class="form-group">
                    {!! Form::normalSelect('sitemap_priority', trans('core::sitemap.title.priority'), $errors, $sitemapPriorities, $category->sitemap_priority) !!}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index', ['name' => 'categories']) }}</dd>
    </dl>
@stop

@section('scripts')
    <script>
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.blog.category.index') ?>" }
                ]
            });
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
        });
    </script>
@stop
