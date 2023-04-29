<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function store(UserProfileRequest $request)
    {
        $userProfile = new User();
        $userProfile->fill($request->validated());
        if ($request->hasFile('profile_image')) {
            $userProfile->profile_image = $request->file('profile_image')->store('profile', 'public');
        }

        $userProfile->save();

        return response()->json([
            'data' => $userProfile->toArray()
        ], 201);
    }

    public function update(UserProfileRequest $request, $id)
    {
        $userProfile = User::findOrFail($id);
        $userProfile->fill($request->validated());

        if ($request->hasFile('profile_image')) {
            Storage::disk('public')->delete('profile/' . $userProfile->profile_image);
            $userProfile->profile_image = $request->file('profile_image')->store('profile', 'public');
        }

        $userProfile->save();

        return response()->json([
            'data' => $userProfile->toArray()
        ], 200);
    }

    public function destroy($id)
    {
        $userProfile = User::findOrFail($id);

        if ($userProfile->profile_image) {
            Storage::disk('public')->delete('profile/' . $userProfile->profile_image);
        }
        $userProfile->delete();
        return response()->json([], 204);
    }
}
