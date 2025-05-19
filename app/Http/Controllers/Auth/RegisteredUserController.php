<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryFile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Storage;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email:strict,dns', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_img' => ['required', 'string'],
        ]);

        $tmp_file = TemporaryFile::where('folder', $request->profile_img)->first();

        if ($tmp_file) {
            $fromPath = 'profile_img/tmp/'.$tmp_file->folder.'/'.$tmp_file->file;
            $toPath = 'profile_img/'.$tmp_file->file;

            Storage::disk('public')->copy($fromPath, $toPath);
            Storage::disk('public')->deleteDirectory('profile_img/tmp/'.$tmp_file->folder);
            $tmp_file->delete();
            $profileImage = $toPath;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_img' => $profileImage,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function process(Request $request)
    {

        if ($request->hasFile('profile_img')) {
            $profile_img = request()->file('profile_img');
            $filename = $profile_img->hashName();
            $folder = uniqid('profile_img', true);
            $profile_img->storeAs('profile_img/tmp/'.$folder, $filename, 'public');
            TemporaryFile::create([
                'folder' => $folder,
                'file' => $filename,
            ]);

            return $folder;
        }

        return '';
    }
}
