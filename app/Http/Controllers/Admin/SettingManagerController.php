<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PAM\Facades\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SettingManagerController extends Controller
{
    public function index(Request $request, $group = null)
    {
        $group = $this->group($group);

        return inertia('Admin/Settings/Manager', [
            'groups' => AdminSetting::groupsOnly(['label', 'key']),
            'group' => AdminSetting::getGroup($group),
            'settings' => AdminSetting::get($group),
        ]);
    }

    public function update(Request $request, $group = null)
    {
        $group = $this->group($group);
        $groupData = AdminSetting::get($group);
        $groupName = Arr::get($groupData, 'label');

        $data = $request->validate(AdminSetting::getValidationRules($group));

        return AdminSetting::fillAndSave($group, $data)
            ? back()->with('success', __('Updated :group settings', ['group' => $groupName]))
            : back()->with('error', __(':group settings cannot be updated', ['group' => ucfirst($groupName)]))->withErrors('Error', 'globalError');
    }

    protected function group($group)
    {
        if (blank($group)) {
            $group = config('admin.settings.default', 'general');
        }

        abort_unless(AdminSetting::groupExists($group), 404);

        return $group;
    }
}
