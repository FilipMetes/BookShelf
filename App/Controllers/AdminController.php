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


    public function setRoles(Request $request): Response
    {
        $admins = $request->value('admins') ?? [];

        foreach (User::getAll() as $user) {
            $user->setRole(in_array($user->getId(), $admins) ? 'A' : 'U');
            $user->save();
        }

        // flash správa
        $this->app->getSession()->set('success', 'Zmeny vykonané');

        // PRG pattern
        return $this->redirect($this->url('admin.index'));
    }


}
