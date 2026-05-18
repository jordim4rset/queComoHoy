<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $recipes = $user->recipes()->paginate(6);

        return view('users.profile', compact('user', 'recipes'));
    }
    public function index()
    {
        return view('users.index');
    }

    public function updateCurrent(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image'],
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            $user->profile_photo = $request->file('profile_photo')->store('img/users/profile', 'public');
        }

        $user->save();

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function search(Request $request)
{
    $q = $request->input('q');
    $users = User::where('username', 'like', "%{$q}%")
        ->limit(6)
        ->get(['id', 'username', 'profile_photo'])
        ->map(fn (User $user) => [
            'id' => $user->id,
            'username' => $user->username,
            'profile_photo_url' => $user->profilePhotoUrl(),
        ]);

    return response()->json($users);
}
}
