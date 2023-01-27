<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CatService
{
    /**
     * Faking Specific URLs
     */
    public function getCat1()
    {
        $response = Http::get('https://thatcopy.pw/catapi/rest/');

        $response->throw();
        // if ($response->status() == 500) {
        //     throw \Exception('Server exception');
        // }

        return Arr::get($response, 'webpurl');
    }

    /**
     * Faking Response Sequences
     */
    public function getCat2()
    {
        $response = Http::get('https://thatcopy.pw/catapi/rest/');

        $response->throw();
        // if ($response->status() == 500) {
        //     throw \Exception('Server exception');
        // }

        return $response->json();
    }

    /**
     * Check Match Response API
     */
    public function getCat4()
    {
        $response = Http::get('https://thatcopy.pw/catapi/rest/');

        $response->throw();
        // if ($response->status() == 500) {
        //     throw \Exception('Server exception');
        // }

        return $response->json();
    }
}
