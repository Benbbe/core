<div class="box-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        {!! Form::label("parent_id", trans("backend.category.sub_category_of"), ['class' => 'label-text']) !!}
        <select class="select2 form-control" id="parent_id" name="parent_id">
        <option value="">{!! trans("backend.category.category_no_parent") !!}</option>
        @foreach (ZEDx\Models\Category::getNestedList('name', 'id', '-') as $id => $name)
        <option value="{{ $id }}" data-category-api-url= "{{ route('zxajax.category.searchFields', $id) }}" {{ $parent_id == $id ? 'selected': '' }}>
        {{ $name }}</option>
        @endforeach
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        {!! Form::label("thumbnail", trans("backend.category.thumbnail.thumbnail"), ['class' => 'label-text']) !!}
        <div class="col-sm-12 parent thumbnail">
          <span class="image">
            @if (isset($category) && $category->thumbnail)
            <img src="{{ public_asset('uploads/categories/' . $category->thumbnail) }}">
            @endif
          </span>
          <input type="hidden" id="oldThumbnail" name="oldThumbnail" value="{{ isset($category) ? $category->thumbnail : "" }}">
          <div class="pull-right">
            <div class="btn btn-xs btn-info btn-file"><i class="fa fa-edit"></i> {{ trans("backend.category.thumbnail.change") }} <input type="file" id="thumbnail" name="thumbnail" data-type="thumbnail" class="edit-image-category"></div>
            <div id="remove-thumbnail" class="btn btn-xs btn-danger" style="{{ isset($category) && $category->thumbnail ? '' : 'display:none;' }}"><i class="fa fa-trash"></i> {{ trans("backend.category.thumbnail.delete") }}</div>
          </div>
        </div>
      </div>
      <hr />
      <div class="form-group">
        {!! Form::label("name", trans("backend.category.category_name"), ['class' => 'label-text']) !!}
        {!! Form::text("name", null, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
        {!! Form::label("is_visible", trans("backend.category.visibility"), ['class' => 'label-text']) !!}
        {!! Form::select("is_visible", [1 => trans('backend.category.visible_category'), 0 => trans('backend.category.hidden_category')], null, ['class' => 'form-control']) !!}
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            {!! Form::label("is_private", trans('backend.category.category_type'), ['class' => 'label-text']) !!}
            {!! Form::select("is_private", array(trans('backend.category.public'), trans('backend.category.protected')), null, ['id' => 'is_private', 'class' => 'form-control']) !!}
          </div>
        </div>
        <div id ='newcodes' class="col-md-4 hide">
            {!! Form::label("nbr_days", "&nbsp;", ['class' => 'label-text']) !!}
          {!! Form::button("<i class='fa fa-plus'></i> <span class='hidden-md'>" . trans('backend.category.add_a_code') . "</span>", ['id' => 'add_code', 'class' => 'btn btn-success form-control']) !!}
        </div>
      </div>
      <hr />
      <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
        <tbody id="codes" class ="hide" data-codes = "{{ isset($codes) ? json_encode($codes) : '[]' }}" data-code-validate-msg = '{"validate": "{!! trans('backend.category.code.validated') !!}", "expired": "{!! trans('backend.category.code.expired') !!}", "reached": "{!! trans('backend.category.code.reached') !!}"}'>
          <tr>
            <th>{!! trans("backend.category.code.code") !!}</th>
            <th>{!! trans("backend.category.code.validity") !!} <span  data-toggle="tooltip" data-original-title="{!! trans("backend.category.code.validity_question") !!}"><i class="fa fa-question-circle"></i></span></th>
            <th>{!! trans("backend.category.code.use") !!} <span data-toggle="tooltip" data-original-title="{!! trans("backend.category.code.max_use_help") !!}"><i class="fa fa-question-circle"></i></span></th>
            <th>{!! trans("backend.category.code.state") !!}</th>
            <th style="width: 40px"></th>
          </tr>
          <script type="x-tmpl-mustache" id="codesTemplate">
          @{{#.}}
            <tr>
              <td><input type="text" class="form-control" name="codes[@{{id}}][code]" placeholder="Votre code" value="@{{code}}"></td>
              <td><input type="text" class="form-control datepicker code_end_date" name="codes[@{{id}}][end_date]" data-date-format="dd/mm/yyyy" value="@{{end_date}}"></td>
              <td><input type="text" class="form-control code_max" name="codes[@{{id}}][max]" placeholder="inf" value="@{{max}}"/></td>
              <td class="code_validate_msg"><small class="label bg-green">{!! trans("backend.category.code.validated") !!}</small></td>
              <td><a href="javascript:void()" class="btn btn-xs btn-danger remove-code"><i class="fa fa-remove"></i> <span class="hidden-md hidden-xs">{!! trans("backend.category.code.delete") !!}</span></span></td>
            </tr>
          @{{/.}}
          </script>
          </tbody>
        </table>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <select id="zedx-fields" name="fields[]" data-selectr-opts='{ "title": "{!! trans("backend.category.field_list") !!}", "placeholder": "{!! trans("backend.category.filter") !!}", "resetText": "{!! trans("backend.category.unselect_all") !!}", "maxSelection": "Infinity" }' multiple>
        @if (isset($category))
        @foreach ($category->fields as $field)
        <option value="{{ $field->id }}" selected ><b>{{ $field->name }}</b> - {{ $field->title }}</option>
        @endforeach
        @endif
        @foreach ($fields as $field)
        <option value="{{ $field->id }}" ><b>{{ $field->name }}</b> - {{ $field->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  @include ('backend::errors.list')
</div>
<div class="box-footer">
  {!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
