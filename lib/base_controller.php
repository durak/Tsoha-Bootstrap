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
            Redirect::to('/login', array('error' => 'Kirjaudu sisään, ole hyvä'));
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
            Redirect::to('/', array('error' => 'Ei oikeuksia!'));
        }
    }

}
