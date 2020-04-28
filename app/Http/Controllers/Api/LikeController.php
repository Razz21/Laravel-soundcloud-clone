<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // morph content type
        $model = Relation::getMorphedModel($request->input('model'));

        if (!(bool) $model) {
            return response('Can not like this content!', 400);
        }

        $target = $model::where('id', $request->input('target_id'))->firstOrFail();
        $like = $target->allLikes()->where('user_id', auth()->user()->id)->first();

        if ($like) {
            // toggle
            if ($like->trashed()) {
                $like->restore();
            } else {
                $like->delete();
            }
        } else {
            // create new like
            $like = $target->likes()->create([
                'user_id' => auth()->user()->id,
            ]);
        }
        return response('', 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
    }

}
