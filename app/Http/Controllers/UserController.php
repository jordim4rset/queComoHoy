<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        $currentUserId = auth()->id();

        $users = User::when($currentUserId, function ($query, $currentUserId) {
            $query->where('id', '!=', $currentUserId);
        })->get();

        return view('users.index', compact('users'));
    }

    public function editCurrent()
    {
        return view('users.edit');
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

    public function destroyCurrent(Request $request): RedirectResponse
    {
        $user = $request->user();

        $filesToDelete = collect([$user->profile_photo])
            ->merge(
                $user->recipes()
                    ->get(['image', 'video'])
                    ->flatMap(fn ($recipe) => [$recipe->image, $recipe->video])
            )
            ->filter()
            ->unique()
            ->values()
            ->all();

        Auth::logout();

        DB::transaction(function () use ($user) {
            $user->following()->detach();
            $user->followers()->detach();
            $user->likes()->delete();
            $user->delete();
        });

        Storage::disk('public')->delete($filesToDelete);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with('success', 'Tu cuenta se ha eliminado correctamente.');
    }

    public function ban(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin($request);

        if ($request->user()->is($user)) {
            return back()->withErrors([
                'ban' => 'No puedes banear tu propio usuario.',
            ]);
        }

        $user->forceFill([
            'banned_at' => now(),
        ])->save();

        return back()->with('success', 'Usuario baneado por tiempo indefinido.');
    }

    public function unban(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin($request);

        $user->forceFill([
            'banned_at' => null,
        ])->save();

        return back()->with('success', 'Usuario desbaneado correctamente.');
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

    private function ensureAdmin(Request $request): void
    {
        if (!$request->user() || $request->user()->rol !== 'admin') {
            abort(403);
        }
    }
}
