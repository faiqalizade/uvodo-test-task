<?php
namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use Core\Request;

class  UserController extends Controller
{
    public function get(Request $request, int $id)
    {
        return ['asd'];
    }

    public function create(Request $request)
    {
        // Todo we should make validation
        $user = User::findOne("email = ?", [$request->email])->getResult();

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
                'message' => 'User created successfully'
            ]
        ];
    }

    public function all()
    {
        return [
            'data' => User::find()->getResult()
        ];
    }


    public function find(int $id)
    {
        $user = User::load($id);
        if ($user->id === 0) {
            return [
                'status' => 404,
                'data' => [
                    'message' => 'User not found'
                ]
            ];
        }
        return [
            'data' => $user->getResult()
        ];
    }

    public function delete(int $id)
    {
        $user = User::load($id);
        if ($user->id === 0) {
            return [
                'status' => 404,
                'data' => [
                    'message' => 'User not found'
                ]
            ];
        }

        User::trash($user->getResult());

        return [
            'data' => [
                'message' => "User removed successfully"
            ]
        ];
    }


    public function edit(Request $request, int $id)
    {
        $newMailUser = User::findOne("email = ?", [$request->email])->getResult();
        if ($newMailUser) {
            return [
                'status' => 400,
                'data' => [
                    'message' => 'Email has been used'
                ]
            ];
        }

        $user = User::load($id);

        if ($user->getResult()->id === 0) {
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
