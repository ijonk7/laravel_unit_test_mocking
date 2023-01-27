<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPost;
use App\Models\Post;
use App\Services\CatService;
use App\Services\CreatePostService;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Throwable;

class PostController extends Controller
{
    private $createPostService;

    public function __construct(CreatePostService $createPost1)
    {
        $this->createPostService = $createPost1;
    }

    public function changeWord1($keyword)
    {
        $word = $this->createPostService->upperCaseFunction($keyword);
        return $word;
    }

    public function changeWord2(CreatePostService $createPost2, $keyword)
    {
        $word = $createPost2->upperCaseFunction($keyword);
        return $word;
    }

    public function changeWord3(CreatePostService $createPost3, $keyword)
    {
        $createPost3->upperCaseFunction($keyword);
        $word2 = $createPost3->lowerCaseFunction($keyword);
        return $word2;
    }

    public function changeWord4()
    {
        $word = 'Hello World';
        return $word;
    }

    public function changeWord5(CreatePostService $createPost5, $keyword)
    {
        $word1 = $createPost5->upperCaseFunction($keyword);
        return $word1;
    }

    public function checkCache(CreatePostService $createPost5)
    {
        $getCache = $createPost5->checkCache();
        return $getCache;
    }

    public function storeCache()
    {
        $storeCache = Cache::put('Kota', 'Bogor');
        return $storeCache;
    }

    public function storeWithJob()
    {
        ProcessPost::dispatch()->delay(now()->addMinutes(1));

        /**
         * Job Chaining
         */
        // Bus::chain([
        //     new ProcessPost,
        //     new ProcessPost,
        // ])->dispatch();

        // $batch = Bus::batch([
        //     new ProcessPost,
        //     new ProcessPost,
        // ])->then(function (Batch $batch) {
        //     // All jobs completed successfully...
        // })->catch(function (Batch $batch, Throwable $e) {
        //     // First batch job failure detected...
        // })->finally(function (Batch $batch) {
        //     // The batch has finished executing...
        // })->dispatch();
        // return $batch;
    }

    public function storeWithEvent()
    {
        ProcessPost::dispatch()->delay(now()->addMinutes(1));
    }

    /**
     * Faking Specific URLs
     */
    public function getCat1(Request $request, CatService $catService)
    {
        if ($request->has('action')) {
            $img = $catService->getCat1();
            return view('welcome', compact('img'));
        }

        return view('welcome');
    }

    /**
     * Faking Response Sequences
     */
    public function getCat2(Request $request, CatService $catService)
    {
        if ($request->has('action')) {
            $img = $catService->getCat2();
            return view('welcome', $img);
        }

        return view('welcome');
    }

    /**
     * Check Match Response API
     */
    public function getCat4(Request $request, CatService $catService)
    {
        $data = $catService->getCat4();
        return $data;
    }
}
