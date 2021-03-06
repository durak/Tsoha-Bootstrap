<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FishController
 *
 * @author itkoskin
 */
class FishController extends BaseController {

    public static function index() {
        $options = array('player_id' => self::get_user_logged_in()->id);
        $fishs = Fish::AllWith($options);

        View::make('fish/index.html', array('fishs' => $fishs));
    }

    public static function show($id) {
        $options = array('player_id' => self::get_user_logged_in()->id, 'fish_id' => $id);
        $fish = Fish::find($options);
        self::check_is_owner($fish);

        View::make('fish/fish_show.html', array('fish' => $fish));
    }

    public static function create() {
        $id = self::get_user_logged_in()->id;
        $trips = Trip::all($id);
        $spots = Spot::all($id);
        $lures = Lure::all($id);
        $speciess = Species::all();

        View::make('fish/new.html', array('trips' => $trips,
            'spots' => $spots, 'lures' => $lures,
            'speciess' => $speciess, 'fishingmethods' => Fish::$FISHING_METHODS));
    }

    public static function store() {
        $attributes = self::postAttributes();
        $fish = new Fish($attributes);
        $errors = $fish->errors();

        if (count($errors) > 0) {
            $id = self::get_user_logged_in()->id;
            $trips = Trip::all($id);
            $spots = Spot::all($id);
            $lures = Lure::all($id);
            $speciess = Species::all();

            View::make('fish/new.html', array('attributes' => $attributes,
                'errors' => $errors, 'trips' => $trips,
                'spots' => $spots, 'lures' => $lures,
                'speciess' => $speciess, 'fishingmethods' => Fish::$FISHING_METHODS));
        } else {

            $fish->save();
            Redirect::to('/fish/' . $fish->id, array('message' => 'Kala lisätty!'));
        }
    }

    public static function edit($id) {
        $options = array('player_id' => self::get_user_logged_in()->id, 'fish_id' => $id);
        $fish = Fish::find($options);
        self::check_is_owner($fish);

        $userid = self::get_user_logged_in()->id;
        $trips = Trip::all($userid);
        $spots = Spot::all($userid);
        $lures = Lure::all($userid);
        $speciess = Species::all();

        View::make('fish/edit.html', array('attributes' => $fish, 'trips' => $trips,
            'spots' => $spots, 'lures' => $lures,
            'speciess' => $speciess, 'fishingmethods' => Fish::$FISHING_METHODS));
    }

    public static function update($id) {
        $options = array('player_id' => self::get_user_logged_in()->id, 'fish_id' => $id);
        self::check_is_owner(Fish::find($options));

        $attributes = self::postAttributes();
        $attributes['id'] = $id;
        $fish = new Fish($attributes);
        $errors = $fish->errors();

        if (count($errors) > 0) {
            $id = self::get_user_logged_in()->id;
            $trips = Trip::all($id);
            $spots = Spot::all($id);
            $lures = Lure::all($id);
            $speciess = Species::all();

            View::make('fish/edit.html', array('attributes' => $attributes,
                'errors' => $errors, 'trips' => $trips,
                'spots' => $spots, 'lures' => $lures,
                'speciess' => $speciess, 'fishingmethods' => Fish::$FISHING_METHODS));
        } else {

            $fish->update();
            Redirect::to('/fish/' . $fish->id, array('message' => 'Kala muokattu.'));
        }
    }

    public static function destroy($id) {
        $options = array('player_id' => self::get_user_logged_in()->id, 'fish_id' => $id);
        $fish = Fish::find($options);
        self::check_is_owner($fish);

        $fish->destroy();
        Redirect::to('/fish', array('message' => 'Kala poistettu'));
    }

    private static function postAttributes() {
        $params = $_POST;
        $attributes = array(
            'player_id' => self::get_user_logged_in()->id,
            'trip_id' => $params['trip_id'],
            'species_id' => $params['species_id'],
            'spot_id' => $params['spot_id'],
            'lure_id' => $params['lure_id'],
            'weight' => $params['weight'],
            'length_cm' => $params['length_cm'],
            'fishing_method' => $params['fishing_method'],
            'fish_description' => $params['fish_description']
        );

        return $attributes;
    }

}
