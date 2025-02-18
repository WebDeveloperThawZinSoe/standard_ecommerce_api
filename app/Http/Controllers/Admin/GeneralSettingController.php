<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Gallery;
use App\Models\SocialAccount;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $generalSettings = GeneralSetting::whereIn('name', [
            'about_image',
            'contact_image',
            'banner_image',
            'banner_link',
            'phone_number_1',
            'phone_number_2',
            'phone_number_3',
            'email_1',
            'email_2',
            'email_3',
            'facebook',
            'telegram',
            'discord',
            'viber',
            'skype',
            'about_us',
            'how_to_sell_us',
            'logo',
            'contact_us',
            'announcement'
        ])->get()->keyBy('name');

        return view('admin.generalSetting.index', compact('generalSettings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'banner_image',
            'about_image',
            'contact_image',
            'banner_link',
            'phone_number_1',
            'phone_number_2',
            'phone_number_3',
            'email_1',
            'email_2',
            'email_3',
            'facebook',
            'telegram',
            'discord',
            'viber',
            'skype',
            'about_us',
            'how_to_sell_us',
            'logo',
            'contact_us',
            'announcement'
        ];

        foreach ($fields as $field) {
            $setting = GeneralSetting::firstOrNew(['name' => $field]);

            if (in_array($field, ['banner_image', 'about_image', 'contact_image','logo'])) {
                if ($request->hasFile($field)) {
                    if ($setting->exists && $setting->value && file_exists(public_path('images/general_settings/' . $setting->value))) {
                        unlink(public_path('images/general_settings/' . $setting->value));
                    }
                    $file = $request->file($field);
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('images/general_settings'), $fileName);
                    $setting->value = $fileName;
                }
            } else {
                $setting->value = $request->input($field);
            }

            $setting->save();
        }

        return redirect()->route('admin.general_settings.index')->with('success', 'General settings updated successfully.');
    }

    public function Bannerstore(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'sort' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:204800',
        ]);
    
        if ($request->hasFile('photo')) {
            $photoName = time() . '-' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('images/photos'), $photoName);
            $photoPath = 'images/photos/' . $photoName;
        }
    
        Gallery::create([
            'name' => $request->name,
            'image' => $photoPath,
            "type" => "banner",
            "sort" => $request->sort
        ]);
        return redirect()->route('admin.general_settings.index')->with('success', 'Add  Banner Image successfully.');
    }
    
    
        public function VideoStore(Request $request){
            $request->validate([
                 'name' => 'required|string|max:255',
                'sort' => 'required|string|max:255',
                'video' => 'required|mimes:mp4,mov,avi,wmv|max:204800', // Validate only video files
            ]);
        
            if ($request->hasFile('video')) {
                $videoName = time() . '-' . $request->file('video')->getClientOriginalName();
                $request->file('video')->move(public_path('videos'), $videoName); // Move to public/videos
                $videoPath = 'videos/' . $videoName;
            }

            Gallery::create([
                'name' => $request->name,
                'image' => $videoPath,
                "type" => "video",
                "sort" => $request->sort
            ]);

             return redirect()->route('admin.general_settings.index')->with('success', 'Video uploaded successfully.');
         }

    public function Bannerdestroy($id){
        $photo = Gallery::findOrFail($id);
        if (file_exists(public_path($photo->image))) {
            @unlink(public_path($photo->image));
        }
        $photo->delete();
        return redirect()->route('admin.general_settings.index')->with('success', ' Delete successfully.');
    }

    public function CreateSocialAccount(Request $request){
        $request->validate([
            'social_name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
       ]);
   
       
       SocialAccount::create([
           'socail_name' => $request->social_name,
           'social_link' => $request->link,
          
       ]);

        return redirect()->route('admin.general_settings.index')->with('success', 'Insert successfully.');
    }

    public function DeleteSocialAccount($id){
        $social = SocialAccount::findOrFail($id);
        
        $social->delete();
        return redirect()->route('admin.general_settings.index')->with('success', ' Delete successfully.');
    }
}
