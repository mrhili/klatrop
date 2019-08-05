<div class="form-group">
	{!! Form::label('name', trans('messages.title')) !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
	{!! Form::label('body', trans('messages.body')) !!}
	{!! Form::textarea('body', null, ['class' => 'form-control wysiwyg', 'required']) !!}
</div>

<div class="form-group">
    <label for="file">{{trans('Attach a file')}}</label>
    {!! Form::file('file', ['class' => 'form-control-file', 'id'=>'file']) !!}
</div>

@include('partials.tags_form')


@include('partials.wysiwyg')
@include('partials.mention')
