<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\User;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\HttpException;

class ProfileController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->html();
    }

    public function save(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        if (!$sessionUser) {
            throw new HttpException(401, "Musíš byť prihlásený.");
        }

        // 🔥 Toto je dôležité – načítame reálny model z DB
        $user = User::getOne($sessionUser->getId());

        if (!$user) {
            throw new HttpException(404, "Používateľ nenájdený.");
        }

        $errors = $this->validateForm($request);
        if (!empty($errors)) {
            return $this->html(compact('user', 'errors'), 'form');
        }

        $user->setName($request->value('name'));
        $user->setSurname($request->value('surname'));
        $user->setCity($request->value('city'));
        $user->setPSC($request->value('PSC'));
        $user->setStreet($request->value('street'));
        $user->setEmail($request->value('e_mail'));

        if ($request->value('password')) {
            $user->setPassword($request->value('password'));
        }

        $user->save();

        // aktualizuj session aby obsahovala nové údaje
        $this->app->getSession()->set(Configuration::IDENTITY_SESSION_KEY, $user);

        $success = "Profil bol úspešne aktualizovaný.";
        return $this->html(compact('user', 'success'), 'index');
    }

    public function edit(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        if (!$sessionUser) {
            throw new HttpException(401, "Musíš byť prihlásený.");
        }

        $user = User::getOne($sessionUser->getId());

        if (!$user) {
            throw new HttpException(404, "Používateľ nenájdený.");
        }

        return $this->html(compact('user'), 'form');
    }

    private function validateForm(Request $request): array
    {
        $errors = [];

        if (!$request->value('name')) $errors[] = "Meno je povinné.";
        if (!$request->value('surname')) $errors[] = "Priezvisko je povinné.";

        if ($PSC = $request->value('PSC')) {
            if (!preg_match('/^\d{5}$/', $PSC)) {
                $errors[] = "PSČ musí byť presne 5 číslic.";
            }
        }

        if (!filter_var($request->value('e_mail'), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Zadaný e-mail nie je platný.";
        }

        if ($pass = $request->value('password')) {
            if (strlen($pass) < 6) $errors[] = "Heslo musí mať aspoň 6 znakov.";
        }

        return $errors;
    }
}
