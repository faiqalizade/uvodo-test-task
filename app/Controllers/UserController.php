<?php
namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use Core\Request;
use JetBrains\PhpStorm\ArrayShape;

class  UserController extends Controller
{
    public function create(Request $request): array
    {
        // Todo we should make validation
        $user = User::findBy([
            'email' => $request->email
        ]);

        if ($user) {
            return [
                'status' => 400,
                'data' => [
                    'message' => 'Email has been used'
                ]
            ];
        }


        $user = new User();
        $user->fill($request->all());
        $user->save();

        return [
            'data' => [
                'message' => 'User created successfully',
                'user' => $user
            ]
        ];
    }

    #[ArrayShape(['data' => "array"])] public function all(): array
    {
        return [
            'data' => User::getAll()
        ];
    }


    public function find(string $id): array
    {
        $user = User::find($id);
        if (!$user) {
            return [
                'status' => 404,
                'data' => [
                    'message' => 'User not found'
                ]
            ];
        }
        return [
            'data' => $user
        ];
    }

    public function delete(string $id): array
    {
        $user = User::find($id);
        if (!$user) {
            return [
                'status' => 404,
                'data' => [
                    'message' => 'User not found'
                ]
            ];
        }

        $user->delete();

        return [
            'data' => [
                'message' => "User removed successfully"
            ]
        ];
    }


    public function edit(Request $request, string $id): array
    {
        $newMailUser = User::findBy([
            'email' => $request->email
        ]);

        if ($newMailUser) {
            return [
                'status' => 400,
                'data' => [
                    'message' => 'Email has been used'
                ]
            ];
        }

        $user = User::find($id);

        if (!$user) {
            return [
                'status' => 404,
                'data' => [
                    'message' => 'User not found'
                ]
            ];
        }

        $user->fill($request->all());
        $user->save();

        return [
            'data' => [
                'message' => "User information saved successfully"
            ]
        ];
    }
}
