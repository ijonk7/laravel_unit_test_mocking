<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CreatePostService
{
    public function upperCaseFunction($keyword)
    {
        $word = Str::of($keyword)->upper();
        return $word;
    }

    public function lowerCaseFunction($keyword)
    {
        $word = Str::of($keyword)->lower();
        return $word;
    }

    public function helloWorld()
    {
        $word = 'Hello World';
        return $word;
    }

    public function checkCache()
    {
        $value = Cache::get('Kota');
        return $value;
    }
}
