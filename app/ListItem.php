<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $fillable = [
        'description',
        'is_done'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_done' => 'boolean',
    ];

    public static function getAllFormatted()
    {
        $list_items = [];
        $stored_list_items = self::all();
        foreach ($stored_list_items as $item) {
            $list_items[] = [
                'id' => $item->id,
                'description' => $item->description,
                'isDone' => $item->is_done,
            ];
        }

        return $list_items;
    }
}
