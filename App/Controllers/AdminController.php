<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class AdminController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn() && $this->user->isAdmin();
    }

    public function index(Request $request): Response
    {
        $success = $this->app->getSession()->get('success');
        $this->app->getSession()->remove('success');

        $users = User::getAll();

        return $this->html(compact('users', 'success'));
    }


    public function setRole(Request $request): Response
    {
        $userId = (int)$request->value('user_id');
        $role = $request->value('role');

        $user = User::getOne($userId);
        if (!$user) {
            return $this->json(['success' => false]);
        }

        $user->setRole($role);
        $user->save();

        return $this->json(['success' => true]);
    }



}
