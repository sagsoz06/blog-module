<div class="box-body">
    <div class="box-body">
        <div class='form-group{{ $errors->has("$lang.title") ? ' has-error' : '' }}'>
            <?php $oldTitle = isset($post->translate($lang)->title) ? $post->translate($lang)->title : ''; ?>
            {!! Form::label("{$lang}[title]", trans('blog::post.form.title')) !!}
            {!! Form::text("{$lang}[title]", old("$lang.title", $oldTitle), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('blog::post.form.title')]) !!}
            {!! $errors->first("$lang.title", '<span class="help-block">:message</span>') !!}
        </div>
        <div class='form-group{{ $errors->has("$lang.slug") ? ' has-error' : '' }}'>
            <?php $oldSlug = isset($post->translate($lang)->slug) ? $post->translate($lang)->slug : ''; ?>
           {!! Form::label("{$lang}[slug]", trans('blog::post.form.slug')) !!}
           {!! Form::text("{$lang}[slug]", old("$lang.slug", $oldSlug), ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => trans('blog::post.form.slug')]) !!}
           {!! $errors->first("$lang.slug", '<span class="help-block">:message</span>') !!}
        </div>

        <?php $oldIntro = isset($post->translate($lang)->intro) ? $post->translate($lang)->intro : ''; ?>
        <textarea class="textarea" name="{{$lang}}[intro]" placeholder="{{ trans('blog::post.form.intro') }}" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
            {!! old("{$lang}.intro", $oldIntro) !!}
        </textarea>

        <?php $oldContent = isset($post->translate($lang)->content) ? $post->translate($lang)->content : ''; ?>
        <textarea class="ckeditor" name="{{$lang}}[content]" rows="10" cols="80">
        {!! old("{$lang}.content", $oldContent) !!}
        </textarea>

        <?php if (config('asgard.blog.config.post.partials.translatable.edit') !== []): ?>
            <?php foreach (config('asgard.blog.config.post.partials.translatable.edit') as $partial): ?>
            @include($partial)
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-primary">
            <div class="box-header">
                <h4 class="box-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo-{{$lang}}">
                        {{ trans('page::pages.form.meta_data') }}
                    </a>
                </h4>
            </div>
            <div style="height: 0px;" id="collapseTwo-{{$lang}}" class="panel-collapse collapse">
                <div class="box-body">
                    {!! Form::i18nInput("meta_title", trans('blog::post.form.meta_title'), $errors, $lang, $post) !!}

                    {!! Form::i18nTextarea("meta_description", trans('blog::post.form.meta_description'), $errors, $lang, $post, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="panel box box-primary">
            <div class="box-header">
                <h4 class="box-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFacebook-{{$lang}}">
                        {{ trans('page::pages.form.facebook_data') }}
                    </a>
                </h4>
            </div>
            <div style="height: 0px;" id="collapseFacebook-{{$lang}}" class="panel-collapse collapse">
                <div class="box-body">
                    {!! Form::i18nInput("og_title", trans('blog::post.form.og_title'), $errors, $lang, $post) !!}

                    {!! Form::i18nInput("og_description", trans('blog::post.form.og_description'), $errors, $lang, $post) !!}

                    {!! Form::i18nSelect("og_type", trans('blog::post.form.og_type'), $errors, $lang, $ogTypes, $post) !!}

                </div>
            </div>
        </div>
    </div>
</div>
