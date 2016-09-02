<div class="dd category-list-{{ $type }} tab-pane fade{{ $active ? ' active in' : '' }}" data-type="{{ $type }}">
  <ol class="dd-list">
    <li class="no-items" style="display: {{ $categories ? 'none' : 'block' }}">No categories found.</li>
    @foreach ($categories as $category)
    <li class="dd-item" data-id="{{ $category['id'] }}" data-order="{{ $category['order'] }}" data-origin-name="{{ $category['name'] }}" data-name="{{ $category['name'] }}" data-cnt-projects-with-children="{{ $category['cnt_projects_with_children'] }}" data-cnt-projects="{{ $category['cnt_projects'] }}" data-parent-id="{{ $category['parent_id'] ? $category['parent_id'] : 0 }}">
      <i class="dd-handle fa fa-arrows"></i>
      <div class="dd3-content">
        <div class="dd-item-header">
          <div class="row">
            <div class="col-sm-8 col-xs-12">
              <h5>{!! $category['name'] !!}<span> ({{ $category['cnt_projects_with_children'] }})</span></h5>
            </div>
            <div class="col-sm-4 col-xs-12">
              <div class="toolbar pull-right">
                <button class="btn-edit btn btn-info btn-xs" type="button"><span>Edit</span><i class="fa fa-edit"></i></button>
                <button class="btn-remove btn btn-danger btn-xs" type="button"{{ $category['cnt_projects_with_children'] ? ' disabled' : '' }}>Remove<i class="fa fa-trash"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if (isset($category['children']))
      <ol class="dd-list">
      @foreach ($category['children'] as $child)
        <li class="dd-item" data-id="{{ $child['id'] }}" data-order="{{ $child['order'] }}" data-origin-name="{{ $child['name'] }}"  data-name="{{ $child['name'] }}" data-cnt-projects-with-children="{{ $child['cnt_projects'] }}" data-cnt-projects="{{ $child['cnt_projects'] }}" data-parent-id="{{ $child['parent_id'] ? $child['parent_id'] : 0 }}">
          <i class="dd-handle fa fa-arrows"></i>
          <div class="dd3-content">
            <div class="dd-item-header">
              <div class="row">
                <div class="col-sm-8 col-xs-12">
                  <h5>{!! $child['name'] !!}<span> ({{ $child['cnt_projects'] }})</span></h5>
                </div>
                <div class="col-sm-4 col-xs-12">
                  <div class="toolbar pull-right">
                    <button class="btn-edit btn btn-info btn-xs" type="button"><span>Edit</span><i class="fa fa-edit"></i></button>
                    <button class="btn-remove btn btn-danger btn-xs" type="button"{{ $child['cnt_projects'] ? ' disabled' : '' }}>Remove<i class="fa fa-trash"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
      @endforeach
      </ol>
      @endif
    </li>
    @endforeach
  </ol>
</div>
