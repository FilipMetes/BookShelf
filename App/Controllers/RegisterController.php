<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\User;
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

    /**
     * @throws Exception
     */
    public function register(Request $request): Response
    {
        $errors = [];

        if ($request->hasValue('submit')) {

            $errors = $this->formErrors($request);

            if (empty($errors)) {
                try {
                    $user = new User();
                    $user->setName(trim($request->value('name')));
                    $user->setSurname(trim($request->value('surname')));
                    $user->setStreet(trim($request->value('street')) ?: null);
                    $user->setCity(trim($request->value('city')) ?: null);
                    $user->setPSC(trim($request->value('PSC')) ?: null);
                    $user->setEmail(trim($request->value('e_mail')));
                    $user->setPassword($request->value('password'));
                    $user->setRole('U');
                    $user->save();

                    return $this->redirect(Configuration::LOGIN_URL);

                } catch (\Exception $ex) {
                    $errors[] = $ex->getMessage();
                }
            }
        }

        return $this->html(compact('errors'), 'index');
    }

    private function formErrors(Request $request): array
    {
        $errors = [];

        $name = trim($request->value('name'));
        $surname = trim($request->value('surname'));
        $e_mail = trim($request->value('e_mail'));
        $password = $request->value('password');

        if ($name === '') {
            $errors[] = 'Meno je povinné.';
        }

        if ($surname === '') {
            $errors[] = 'Priezvisko je povinné.';
        }

        if (!filter_var($e_mail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Neplatný email.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Heslo musí mať aspoň 6 znakov.';
        }

        if (User::getCount('e_mail = ?', [$e_mail]) > 0) {
            $errors[] = 'Používateľ s týmto emailom už existuje.';
        }

        return $errors;
    }


}
