<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author itkoskin
 */
class UserController extends BaseController {

    public static function login() {
        View::make('user/login.html');
    }

    public static function handle_login() {
        $params = $_POST;
        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana',
                'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->id;
            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username));
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos'));
    }

    public static function signup() {
        View::make('user/signup.html');
    }

    public static function handle_signup() {
        $params = $_POST;
        $attributes = array('username' => $params['username'], 'password' => $params['password'], 'password_confirmation' => $params['password_confirmation']);

        $user = new User($attributes);
        $errors = $user->errors();

        if (count($errors) > 0) {

            View::make('user/signup.html', array('errors' => $errors,
                'username' => $params['username']));
        } else {
            $user->save();
            $_SESSION['user'] = $user->id;
            Redirect::to('/', array('message' => 'Tervetuloa KalaDB:n käyttäjäksi ' . $user->username));
        }
    }

    public static function show($id) {
        self::check_is_user_themselves($id);
        $user = User::find($id);

        View::make('user/user_show.html', array('user' => $user));
    }

    public static function update($id) {
        self::check_is_user_themselves($id);
        $params = $_POST;
        $user = User::find($id);

        $user->username = $params['username'];
        $errors = $user->validate_username();

        if (count($errors) > 0) {

            View::make('user/user_show.html', array('errors' => $errors, 'user' => $user));
        } else {

            $user->update();
            Redirect::to('/', array('message' => 'Käyttäjänimi muokattu, morjensta ' . $user->username . '!'));
        }
    }

    public static function destroy($id) {
        self::check_is_user_themselves($id);
        $user = User::find($id);
        $user->destroy();
        $_SESSION['user'] = null;
        
        Redirect::to('/', array('message' => "Hei hei!"));
    }

}
