<?php

class EngagePicknPayCategory extends Eloquent {

    protected $connection = 'hipengage';

    public function getCategories($id) {
        return $this::where('store_id', $id);
    }
}