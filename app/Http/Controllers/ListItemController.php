<?php

namespace App\Http\Controllers;

use App\ListItem;
use Illuminate\Http\Request;

class ListItemController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function pull()
    {
        $list_items  = ListItem::getAllFormatted();
        $next_id = ListItem::max('id') + 1;

        return response()->json([
            'status' => 'success',
            'listItems' => $list_items,
            'nextItemId' => $next_id
        ]);
    }

    public function push(Request $request)
    {
        $validated = $request->validate([
            'listItems' => 'array',
            'listItems.*' => 'array',
            'listItems.*.id' => 'integer',
            'listItems.*.description' => 'string',
            'listItems.*.isDone' => 'boolean',
        ]);

        $remaining_ids = [];

        foreach ($validated['listItems'] as $list_item) {
            ListItem::updateOrCreate(['id' => $list_item['id']],[
                'description' => $list_item['description'],
                'is_done' => $list_item['isDone']
            ]);
            $remaining_ids[] = $list_item['id'];
        }

        ListItem::whereNotIn('id',$remaining_ids)->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
