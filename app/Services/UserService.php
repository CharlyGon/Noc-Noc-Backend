<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        try {
            $randomPassword = Str::random(10);
            $newUser = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($randomPassword),
            ]);

            Log::info ('password hash: ' . $newUser->password);
            Log::info ('passwor Randon: ' . $randomPassword);
            $newUser->password = $randomPassword;

            return $newUser;
        } catch (\Exception $e) {
            throw new \Exception("Error al crear el usuario: " . $e->getMessage());
        }
    }

    /**
     * Get all users.
     *
     * @return User[]
     */
    public function getAllUsers()
    {
        try {
            return User::all();
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener todos los usuarios: " . $e->getMessage());
        }
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User
     */
    public function getUserById($id)
    {
        try {
            return User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Usuario no encontrado con el ID: " . $id);
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener el usuario con el ID " . $id . ": " . $e->getMessage());
        }
    }

    /**
     * Get a user by email.
     *
     * @param string $email
     * @return User
     */
    public function getUserByEmail($email)
    {
        try {
            return User::where('email', $email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Usuario no encontrado con el correo electrónico: " . $email);
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener el usuario con el correo electrónico" . $email . ": " . $e->getMessage());
        }
    }

    /**
     * Update a user.
     *
     * @param array $data
     * @param User $user
     * @return User
     */
    public function updateUser(array $data, User $user): User
    {
        try {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
                $data['password_changed_at'] = now();
            }

            $user->update($data);

            return $user;
        } catch (\Exception $e) {
            throw new \Exception("Error al actualizar el usuario: " . $e->getMessage());
        }
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            throw new \Exception("Error al eliminar el usuario: " . $e->getMessage());
        }
    }
}
