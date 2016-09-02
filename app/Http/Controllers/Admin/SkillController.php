<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

// Models
use Wawjob\Skill;

class SkillController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show all skills.
   *
   * @return Response
   */
  public function all()
  {
    $skills = Skill::withTrashed()->orderBy('name', 'asc')->get();

    return view('pages.admin.system.skill.list', [
      'page' => 'skill.list',
      'css' => 'skill.list',
      'skills' => $skills,

      'j_trans'=> [
        'remove_skill' => trans('j_message.admin.skill.remove_skill'), 
        'deactivate_skill' => trans('j_message.admin.skill.deactivate_skill'), 
      ]
    ]);
  }

  /**
   * Check if a skill is deactivatable(ajax).
   *
   * @return Response
   */
  public function deactivatable(Request $request)
  {
    $id = intval($request->input('id'));

    if (!$id) {
      return response()->json([
        'success' => false,
        'msg' => trans('page.errors.action_not_allowed'),
      ]);
    }

    $skill = Skill::find($id);

    if (!$skill) {
      return response()->json([
        'success' => false,
        'msg' => trans('page.errors.ask_refresh'),
      ]);
    }

    if ($skill->users->count() || $skill->projects->count()) {
      return response()->json([
        'success' => false,
        'msg' => trans('page.admin.skill.list.in_use', ['skill' => $skill->name]),
      ]);
    }

    return response()->json([
      'success' => true,
    ]);
  }

  /**
   * Save the changes(ajax).
   *
   * @return Response
   */
  public function save(Request $request)
  {
    $reflects = [];
    $changes = $request->input('changes');

    foreach ($changes as $id => $change) {
      $reflects[$id] = ['msgs' => []];

      // Add
      if (!is_int($id)) {
        if (isset($change['remove'])) {
          continue;
        }

        $skill = Skill::findOrNew($id);
        $skill->name = $change['name'];
        $skill->desc = strip_tags($change['desc'], '<kp><en><ch>');

        $skill->save();

        $reflects[$id]['id'] = $skill->id;
        $reflects[$id]['msgs'][] = [
          'msg' => trans('page.admin.skill.list.added', ['skill' => $change['name']]),
          'type' => 'success',
        ];

        if (isset($change['active'])) {
          if ($change['active']) {
            $skill->restore();
          } else {
            $skill->delete();
            $reflects[$id]['msgs'][] = [
              'msg' => trans('page.admin.skill.list.deactivated', ['skill' => $change['name']]),
              'type' => 'info',
            ];
          }
        }

        continue;
      }

      // Update
      $skill = Skill::withTrashed()->find(intval($id));

      if (!$skill) {
        continue;
      }

      if (isset($change['remove'])) {
        $reflects[$id]['msgs'][] = [
          'msg' => trans('page.admin.skill.list.removed', ['skill' => isset($change['name']) ? $change['name'] : $skill->name]),
          'type' => 'danger',
        ];
        $skill->forceDelete();
        continue;
      }

      if (isset($change['name'])) {
        $skill->name = $change['name'];
      }
      if (isset($change['desc'])) {
        $skill->desc = strip_tags($change['desc'], '<kp><en><ch>');
      }

      if (isset($change['name']) || isset($change['desc'])) {
        $skill->save();

        $reflects[$id]['msgs'][] = [
          'msg' => trans('page.admin.skill.list.updated', ['skill' => $skill->name]),
          'type' => 'success',
        ];
      }

      if (isset($change['active'])) {
        if ($change['active']) {
          $skill->restore();
          $reflects[$id]['msgs'][] = [
            'msg' => trans('page.admin.skill.list.activated', ['skill' => $skill->name]),
            'type' => 'info',
          ];
        } else if ($skill->users->count() || $skill->projects->count()) {
          $reflects[$id]['msgs'] = [
            'msg' => trans('page.admin.skill.list.in_use', ['skill' => $skill->name]),
            'type' => 'info',
          ];
        } else {
          $skill->delete();
          $reflects[$id]['msgs'][] = [
            'msg' => trans('page.admin.skill.list.deactivated', ['skill' => $skill->name]),
            'type' => 'info',
          ];
        }
      }
    }

    return response()->json([
      'reflects' => $reflects
    ]);
  }
}