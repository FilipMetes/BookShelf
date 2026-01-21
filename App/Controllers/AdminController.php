<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Exception;

class AdminController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn() && $this->user->isAdmin();
    }

    /**
     * @throws Exception
     */
    public function index(Request $request): Response
    {
        if (!$this->user->isLoggedIn() || !$this->user->isAdmin()) {
            return $this->redirect('home.index');
        }

        $success = $this->app->getSession()->get('success');
        $this->app->getSession()->remove('success');

        $users = User::getAll();

        return $this->html(compact('users', 'success'));
    }


    /**
     * @throws Exception
     */
    public function setRoles(Request $request): Response
    {
        if (!$this->user->isLoggedIn() || !$this->user->isAdmin()) {
            return $this->redirect('home.index');
        }

        /*
            Vypracované s pomocou AI
        */
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: [];
        /*
            koniec AI
        */
        $admins = $data['admins'] ?? [];

        foreach (User::getAll() as $user) {
            // preskočíme seba
            if ($user->getId() === $this->user->getId()) {
                continue;
            }

            $user->setRole(in_array($user->getId(), $admins) ? 'A' : 'U');
            $user->save();
        }


        return $this->json(['success' => true]);
    }








}
