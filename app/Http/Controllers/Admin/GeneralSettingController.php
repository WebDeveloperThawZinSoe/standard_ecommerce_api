<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Gallery;
use App\Models\SocialAccount;
use Illuminate\Support\Str;

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
            "icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'social_name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);
    
        // Store the uploaded file
        // if ($request->hasFile('icon')) {
        //     // Generate a unique file name
        //     $iconName = Str::random(40) . '.' . $request->file('icon')->getClientOriginalExtension();
        //     $iconPath = $request->file('icon')->storeAs('public/images/icon', $iconName); // Save icon in the specified directory
        // } else {
        //     return redirect()->back()->with('error', 'Icon upload failed.');
        // }

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $thumbnailName = 'icon_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('icon'), $thumbnailName);
            $iconPath = 'icon/' . $thumbnailName;
        }
    
        // Create a new social account entry
        SocialAccount::create([
            'socail_name' => $request->social_name,
            'social_link' => $request->link,
            'icon' => $iconPath, // Save the stored file path in the database
        ]);
    
        return redirect()->route('admin.general_settings.index')->with('success', 'Insert successfully.');
    }
    
    
    public function DeleteSocialAccount($id){
        $social = SocialAccount::findOrFail($id);
        
        $social->delete();
        return redirect()->route('admin.general_settings.index')->with('success', ' Delete successfully.');
    }
}
