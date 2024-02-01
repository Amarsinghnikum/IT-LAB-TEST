<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\AudioHelper;
use App\Helpers\DistanceHelper;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Image;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|numeric|digits:10',
            'profile_pic' => 'nullable|mimes:png,jpg,jpeg',
            'password' => 'required|min:8',
        ]);

        $user = User::create($request->all());

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('profile_pics', $filename);
            $user->update(['profile_pic' => $filename]);
        }

        return response()->json(['message' => 'User created successfully.']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|numeric|digits:10',
            'profile_pic' => 'nullable|mimes:png,jpg,jpeg',
            'password' => 'nullable|min:8',
        ]);        

        $user = User::findOrFail($id);
        $userData = $request->except('profile_pic');
    
        $user->update($userData);
    
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('profile_pics', $filename);
            $user->update(['profile_pic' => $filename]);
        }
    
        return response()->json(['message' => 'User updated successfully.']);
    }    

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->profile_pic) {
            $path = public_path('/profile_pics/' . $user->profile_pic);
        
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
    
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('mobile', 'like', '%' . $query . '%')
                    ->get();

        return response()->json($users);
    }

    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function getAudioLength()
    {
        $filePath = public_path('mp3/sample.mp3');
        $playtime = AudioHelper::getAudioLength($filePath);

        return view('audio')->with('playtime', gmdate("H:i:s", $playtime));
    }

    public function showDistance()
    {
        $lat1 = 37.7749;
        $lon1 = -122.4194;
    
        $lat2 = 34.0522;
        $lon2 = -118.2437;
    
        $distance = DistanceHelper::haversineDistance($lat1, $lon1, $lat2, $lon2);
    
        return view('distance')->with('distance', $distance);
    }
}
