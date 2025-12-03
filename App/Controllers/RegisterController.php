<?php

namespace App\Controllers;

use App\Configuration;
use Exception;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class RegisterController extends BaseController
{
    public function index(Request $request): Response
    {
        // show register form
        return $this->html();
    }

    public function create(Request $request): Response
    {
        $errors = [];
        if ($request->hasValue('submit')) {
            $name = trim($request->value('name'));
            $surname = trim($request->value('surname'));
            $street = trim($request->value('street')) ?: null;
            $city = trim($request->value('city')) ?: null;
            $PSC = trim($request->value('PSC')) ?: null;
            $e_mail = trim($request->value('e_mail'));
            $password = $request->value('password');

            if ($name === '') $errors[] = 'Meno je povinné.';
            if ($surname === '') $errors[] = 'Priezvisko je povinné.';
            if (!filter_var($e_mail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Neplatný email.';
            if (strlen($password) < 6) $errors[] = 'Heslo musí mať aspoň 6 znakov.';
            if (\App\Models\User::getCount('e_mail = ?', [$e_mail]) > 0) $errors[] = 'Používateľ s týmto emailom už existuje.';

            if (empty($errors)) {
                try {
                    $user = new \App\Models\User();
                    $user->setName($name);
                    $user->setSurname($surname);
                    $user->setStreet($street);
                    $user->setCity($city);
                    $user->setPSC($PSC);
                    $user->setEmail($e_mail);
                    $user->setPassword($password);
                    $user->setRole('U');
                    $user->save();

                    return $this->redirect(Configuration::LOGIN_URL);
                } catch (\Exception $ex) {
                    $errors[] = $ex->getMessage();
                }
            }
        }

        return $this->html(compact('errors'));
    }
}

