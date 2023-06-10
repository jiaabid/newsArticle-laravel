<?php

namespace App\Http\Controllers;

use App\Models\UserAuthor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserSource;

class UserController extends Controller
{
    //
    /**
     * Save the user preferences for source and authors
     * category is not included in API data so it is not included
     */
    public function savePreferences(Request $request)
    {
        //
        // $this->validate($request, [
        //     'sources' => 'required|'
        // ]);
        $user = Auth::user();

        //format the source payload for bulk insert
        $sourcePayload = collect([]);
        foreach ($request->sources as $source) {
            $sourcePayload->push([
                "user_id" => $user->id,
                "sources" => $source
            ]);
        }
        //format the author payload for bulk insert
        $authorPayload = collect([]);
        foreach ($request->authors as $author) {
            $authorPayload->push([
                "user_id" => $user->id,
                "author" => $author
            ]);
        }

        $source = UserSource::insert($sourcePayload->toArray());
        $author = UserAuthor::insert($authorPayload->toArray());
        return self::success("Preference saved", [$source]);
    }
}
