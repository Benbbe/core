<?php

namespace ZEDx\Http\Controllers\Backend;

use Auth;
use File;
use Image;
use Intervention\Image\Exception\NotReadableException;
use ZEDx\Events\Setting\SettingWasUpdated;
use ZEDx\Events\Setting\SettingWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\SettingRequest;
use ZEDx\Models\Country;
use ZEDx\Models\Language;
use ZEDx\Models\Setting;

class SettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function index()
    {
        $setting = setting();
        $currencies = Country::distinct()->where('currency', '<>', '')
            ->groupBy('currency')
            ->lists('currency')
            ->toArray();

        $languages = $this->getLanguages();

        return view_backend('setting.index', compact('setting', 'currencies', 'languages'));
    }

    /**
     * Get Languages.
     *
     * @return array
     */
    protected function getLanguages()
    {
        $languages = [];
        $coreLanguagesPath = File::directories(core_path('resources/lang'));
        $customLanguagesPath = File::directories(base_path('resources/lang/core'));

        $paths = array_merge($coreLanguagesPath, $customLanguagesPath);

        foreach ($paths as $path) {
            $code = basename($path);

            if (isset($languages[$code])) {
                continue;
            }

            $languageName = Language::whereCode(strtoupper($code))->first();
            $languages[$code] = $languageName ? $languageName->name : ucfirst($code);
        }

        return $languages;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(SettingRequest $request)
    {
        if (env('VERSION_DEMO', false)) {
            return back()->withErrors(['demo_update_settings' => trans('backend.demo.update_settings')]);
        }

        $admin = Auth::guard('admin')->user();
        $setting = setting();
        $inputs = $request->all();
        $inputs['social_auths'] = json_encode($inputs['social_auths']);
        $setting->fill($inputs);
        event(new SettingWillBeUpdated($setting, $admin));
        $setting->save();
        event(new SettingWasUpdated($setting, $admin));

        $this->setToEnv($request);
        $this->saveUploads($request);

        return redirect()->route('zxadmin.setting.index')->with('message', 'success');
    }

    /**
     * Save new logo & watermark images.
     *
     * @param SettingRequest $request
     *
     * @return void
     */
    protected function saveUploads(SettingRequest $request)
    {
        if ($request->hasFile('logo')) {
            $this->uploadImage($request->file('logo'), public_path('logo.png'));
        }

        if ($request->hasFile('watermark')) {
            $this->uploadImage($request->file('watermark'), public_path('uploads/watermark.png'));
        }
    }

    protected function uploadImage($image, $path)
    {
        try {
            $img = Image::make($image);
        } catch (NotReadableException $e) {
            return;
        }

        $img->save($path, 100);
    }

    /**
     * Set settings to Environement file.
     *
     * @param Setting $setting
     */
    protected function setToEnv(SettingRequest $request)
    {
        $setting = setting();
        $this->setProvidersToEnv(json_decode($setting->social_auths));
        $this->setLanguageToEnv($setting->language);
        $this->setAdImagesSettingToEnv($request->image_settings);
    }

    /**
     * Set images setting to the environement file.
     *
     * @param StdClass $image_settings
     *
     * @return void
     */
    protected function setAdImagesSettingToEnv($imageSettings)
    {
        foreach ($imageSettings as $key => $value) {
            env_replace('ZEDX_IMAGES_'.$key, starts_with($value, '#') ? substr($value, 1) : $value);
        }
    }

    /**
     * Set providers keys to the environement file.
     *
     * @param StdClass $providers
     *
     * @return void
     */
    protected function setProvidersToEnv($providers)
    {
        foreach ($providers as $providerName => $provider) {
            env_replace(strtoupper($providerName).'_CLIENT_ID', $provider->client_id);
            env_replace(strtoupper($providerName).'_CLIENT_SECRET', $provider->secret_key);
        }
    }

    /**
     * Set language to environement file.
     *
     * @param string $language
     */
    protected function setLanguageToEnv($language)
    {
        env_replace('APP_LOCALE', $language);
    }
}
