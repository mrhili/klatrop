<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Image;
use Storage;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a settings edition screen. Currently only the homepage intro text, but this will change soon :-).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update settings from the edit form.
     */
    public function update(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            \App\Setting::set('name', $request->get('name'));
            \App\Setting::set('homepage_presentation', $request->get('homepage_presentation'));
            \App\Setting::set('homepage_presentation_for_members', $request->get('homepage_presentation_for_members'));
            \App\Setting::set('help_text', $request->get('help_text'));
            \App\Setting::set('user_can_create_groups', $request->has('user_can_create_groups') ? 1 : 0);
            \App\Setting::set('user_can_create_secret_groups', $request->has('user_can_create_secret_groups') ? 1 : 0);
            \App\Setting::set('notify_admins_on_group_create', $request->has('notify_admins_on_group_create') ? 1 : 0);
            \App\Setting::set('user_can_post_by_email', $request->has('user_can_post_by_email') ? 1 : 0);
            \App\Setting::set('mail_prefix', $request->get('mail_prefix'));
            \App\Setting::set('mail_suffix', $request->get('mail_suffix'));
            \App\Setting::set('mail_server', $request->get('mail_server'));
            \App\Setting::set('mail_login', $request->get('mail_login'));
            if (strlen($request->get('mail_password')) > 5) {
                \App\Setting::set('mail_password', $request->get('mail_password'));
            }

            // handle app logo
            if ($request->hasFile('logo')) {
                Storage::makeDirectory('public/logo');
                Image::make($request->file('logo'))->fit(128, 128)->save(storage_path().'/app/public/logo/favicon.png');
                Image::make($request->file('logo'))->fit(640, 640)->save(storage_path().'/app/public/logo/logo.jpg');
            }

            flash('Settings saved');

            return view('admin.settings.index');
        } else {
            flash(trans('messages.not_allowed'));

            return redirect()->action('DashboardController@index');
        }
    }
}
