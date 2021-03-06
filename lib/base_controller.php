<?php

class BaseController {

    public static function get_user_logged_in() {
        // Toteuta kirjautuneen käyttäjän haku tähän
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            $user = User::find($user_id);

            return $user;
        }
        return null;
    }

    public static function check_logged_in() {
        // Toteuta kirjautumisen tarkistus tähän.
        // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
        if (!self::get_user_logged_in()) {
            $errors = array();
            $errors[] = 'Kirjaudu sisään, ole hyvä.';
            Redirect::to('/login', array('errors' => $errors));
        }
    }

    public static function match_logged_user($id) {
        $user = self::get_user_logged_in();
        $user_id = $user->id;
        if ($user_id != $id) {
            return false;
        }
        return true;
    }

    public static function check_is_owner($object) {
        if (!$object or ! self::match_logged_user($object->player_id)) {
            $errors = array();
            $errors[] = 'Ei oikeuksia!';
            Redirect::to('/', array('errors' => $errors));
        }
    }

    public static function check_is_user_themselves($id) {
        if (!self::match_logged_user($id)) {
            $errors = array();
            $errors[] = 'Ei oikeuksia!';
            Redirect::to('/', array('errors' => $errors));
        }
    }

    public static function check_exists($object) {
        if (!$object) {
            $errors = array();
            $errors[] = 'Ei oikeuksia!';
            Redirect::to('/', array('errors' => $errors));
        }
    }

}
