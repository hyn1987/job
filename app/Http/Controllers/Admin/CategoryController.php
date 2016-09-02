<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

// Models
use Wawjob\Category;

class CategoryController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show all categories.
   *
   * @return Response
   */
  public function all()
  {
    $data = [];

    foreach (Category::$type_list as $type) {
      $data[$type] = Category::byType($type);
    }

    return view('pages.admin.system.category.list', [
      'page' => 'category.list',
      'css' => 'category.list',
      'data' => $data,

      'j_trans'=> [
          'remove_category' => trans('j_message.admin.category.remove_category'), 
        ]
    ]);
  }

  /**
   * Save one category.
   */
  private function _saveOne($type, $id, $category)
  {
    // Remove
    if (isset($category['removed'])) {
      if (is_int($id)) {
        $category = Category::find($id);

        if ($category) {
          $project_count = $category->projects->count();

          $children = Category::where('parent_id', $id)->get();
          $ids = [$id];
          if ($children) {
            foreach ($children as $child) {
              $project_count += $child->projects->count();
              $ids[] = $child->id;
            }
          }

          // There are still projects existed.
          if ($project_count) {
            return trans('page.admin.category.list.projects_exist', ['number' => $project_count]);
          }

          Category::find($id)->delete();
        }
      }
      return null;
    }

    // Add
    if (!is_int($id)) {
      $cat = Category::findOrNew($id);
      $cat->type = $type;
      $cat->name = isset($category['name']) ? $category['name'] : '';
      $cat->desc = null;
      $cat->parent_id = !isset($category['parent']) || (isset($category['parent']) && $category['parent'] == 0) ? null : $category['parent'];
      $cat->order = isset($category['order']) ? $category['order'] : 0;

      $cat->save();

      return $cat->id;
    }

    // Edit
    if (is_int($id)) {
      $cat = category::find($id);

      if (isset($category['name'])) {
        $cat->name = $category['name'];
      }
      if (isset($category['order'])) {
        $cat->order = $category['order'];
      }
      if (isset($category['parent'])) {
        $cat->parent_id = !isset($category['parent']) || (isset($category['parent']) && $category['parent'] == 0) ? null : $category['parent'];
      }

      $cat->save();
    }

    return null;
  }

  /**
   * Save the changes(ajax).
   *
   * @return Response
   */
  public function save(Request $request)
  {
    $data = $request->input('data');
    $type = intval($data['type']);
    $categories = $data['categories'];

    $result = [
      'msg' => [],
      'map' => [],
    ];

    foreach ($categories as $id => $category) {
      $returned = $this->_saveOne($type, $id, $category);
      if (is_int($returned)) {
        $result['map'][$id] = $returned;
        $id = $returned;
      } else if (is_string($returned)) {
        $result['msg'][] = $returned;
      }
      if (isset($category['children'])) {
        foreach ($category['children'] as $cid => $child) {
          if (isset($child['parent'])) {
            $child['parent'] = $id;
          }
          $returned = $this->_saveOne($type, $cid, $child);
          if (is_int($returned)) {
            $result['map'][$cid] = $returned;
          } else if (is_string($returned)) {
            $result['msg'][] = $returned;
          }
        }
      }
    }

    $success = true;
    if (!empty($result['msg'])) {
      foreach ($result['msg'] as $message) {
        $msg .= '<span>' . $message . '</span>';
      }
    } else {
      $msg = trans('page.admin.category.list.success_update');
    }
    return response()->json([
      'success' => $success,
      'msg' => $msg,
      'map' => $result['map'],
    ]);
  }
}