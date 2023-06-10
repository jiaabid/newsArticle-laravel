<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserSource;
use App\Models\UserAuthor;

class ArticleController extends Controller
{
    //
    /**
     * Retrieve all the articles from the API
     */
    public function newsapiArticles(Request $request)
    {
        try {
            $queryObject = $request->query();
            $queryString = '';
            foreach ($queryObject as $parameters => $value) {
                $query = '' . $parameters . '=' . $value;
                $queryString = strlen($queryString) == 0 ? $queryString . $query : $queryString . '&' . $query;
                // $queryString = $queryString.$parameters.$value;
            };
            $response =  $this->getArticleData($queryString,'newsapi');
            $responseJson = $response->json();
            return self::success("Articles retrieved!", ["data" => $responseJson]);
        } catch (Error $err) {
            return self::failure("Internal Server Error", $err, 203);
        }
    }

    //
    /**
     * Retrieve all the articles from the API
     */
    public function nytimesArticles(Request $request)
    {
        try {
            // $response =  Http::get('https://api.nytimes.com/svc/search/v2/articlesearch.json?q=technology&api-key=' . env("NEWYORKTIMES_KEY"));
            // $responseJson = $response->json();
            // return $responseJson;
            $queryObject = $request->query();
            $queryString = '';
            foreach ($queryObject as $parameters => $value) {
                $query = '' . $parameters . '=' . $value;
                $queryString = strlen($queryString) == 0 ? $queryString . $query : $queryString . '&' . $query;
                // $queryString = $queryString.$parameters.$value;
            };
            $response =  $this->getArticleData($queryString,'nytimes');
            $responseJson = $response->json();
            return self::success("Articles retrieved!", ["data" => $responseJson]);
        } catch (Error $err) {
            return self::failure("Internal Server Error", $err, 203);
        }
    }

  /**
     * Internal function to call the external API and return the data
     */
    function getArticleData($query = null, $apiname)
    {
        $baseUrl = "";
        $apiKey = "";
        switch ($apiname) {
            case "newsapi":
                $baseUrl = env('NEWSAPI_URL');
                $apiKey = env('NEWS_API_KEY');
                break;
            case "nytimes":
                $baseUrl = env('NEWYORKTIMES_URL');
                $apiKey = env('NEWYORKTIMES_KEY');
                break;
        }
        if ($query) {
            $apiUrl = $baseUrl . $query . '&' . $apiKey;
        } else {
            $apiUrl = $baseUrl . 'q=technology&' . $apiKey;
        }
        $response =  Http::get($apiUrl);
        return $response;
    }


    /**
     * Retrieve the article data on basis of saved preferences
     */
    public function personalFeed()
    {
        //
        // $this->validate($request, [
        //     'sources' => 'required|'
        // ]);
        $user = Auth::user();
        $source = UserSource::where('user_id', $user->id)->get()->pluck('sources');
        $authors = UserAuthor::where('user_id', $user->id)->get()->pluck('author');
        $articles =  $this->getArticleData(null,"newsapi");
        // return $articles;
        $articleCollection = collect($articles["articles"]);
        $sourceArticles = $articleCollection->whereIn("source.name", $source);
        $authorArticles = $articleCollection->whereIn("author", $authors);
        return self::success("Customized Feed retrieved!", ["sources" => $sourceArticles->values()->all(), "author" => $authorArticles->values()->all()]);
    }
}
