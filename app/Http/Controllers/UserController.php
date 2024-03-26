<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json(['users' => $users]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = $this->userService->createUser($validatedData);

        return response()->json(['message' => 'User successfully created', 'user' => $user]);
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return response()->json(['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = $this->userService->getUserById($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $updatedUser = $this->userService->updateUser($validatedData, $user);

        return response()->json(['message' => 'User successfully updated', 'user' => $updatedUser]);
    }

    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);
        $this->userService->deleteUser($user);

        return response()->json(['message' => 'User successfully deleted']);
    }
}
