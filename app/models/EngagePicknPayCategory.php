<?php

class EngagePicknPayCategory extends Eloquent {

    protected $connection = 'hipengage';

    public static function getCategories($id) {
        return $this::where('store_id', $id);
    }
}