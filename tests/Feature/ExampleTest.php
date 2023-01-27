<?php

namespace Tests\Feature;

use App\Jobs\ProcessPost;
use App\Services\CreatePostService;
use Illuminate\Bus\PendingBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function uppercase_word_1()
    {
        $this->withoutExceptionHandling();

        $this->mock(CreatePostService::class, function (MockInterface $mock) {
            $mock->shouldReceive('upperCaseFunction')
                 ->once()
                 ->with('hasan')
                 ->andReturn('HASAN');
        });

        $response = $this->get('/change-word-1/hasan');
        $response->assertStatus(200);
        $response->assertSee('HASAN');
    }

    /** @test */
    public function uppercase_word_2()
    {
        $this->withoutExceptionHandling();

        $this->mock(CreatePostService::class, function (MockInterface $mock) {
            $mock->shouldReceive('upperCaseFunction')
                 ->once()
                 ->with('hasan')
                 ->andReturn('HASAN');
        });

        $response = $this->get('/change-word-2/hasan');
        $response->assertStatus(200);
        $response->assertSee('HASAN');
    }

    /** @test */
    public function lower_upper_case_word_1()
    {
        $this->withoutExceptionHandling();

        $this->partialMock(CreatePostService::class, function (MockInterface $mock) {
            $mock->shouldReceive('upperCaseFunction')
                 ->once()
                 ->with('GooGle')
                 ->andReturn('google');
        });

        $response1 = $this->get('/change-word-2/hasan');
        $response1->assertStatus(200);
        $response1->assertSee('HASAN');

        $response2 = $this->get('/change-word-3/GooGle');
        $response2->assertStatus(200);
        $response2->assertSee('google');
    }

    /** @test */
    public function lower_upper_case_word_2()
    {
        $this->withoutExceptionHandling();

        $spy = $this->spy(CreatePostService::class);

        $spy->shouldReceive('upperCaseFunction')
                 ->once()
                 ->with('GooGle')
                 ->andReturn('GOOGLE');

        $response1 = $this->get('/change-word-5/GooGle');
        $response1->assertStatus(200);
        $response1->assertSee('GOOGLE');

        $spy->shouldHaveReceived('upperCaseFunction');
    }

    /** @test */
    public function check_cache()
    {
        $this->withoutExceptionHandling();

        Cache::shouldReceive('get')
                 ->once()
                 ->with('Kota')
                 ->andReturn('Bogor');

        $response = $this->get('/check-cache');
        $response->assertStatus(200);
        $response->assertSee('Bogor');
    }

    /** @test */
    public function store_cache()
    {
        $this->withoutExceptionHandling();

        Cache::spy();
        $response = $this->get('/store-cache');
        $response->assertStatus(200);
        Cache::shouldHaveReceived('put')->once()->with('Kota', 'Bogor');
    }

    /** @test */
    public function post_can_be_create()
    {
        $this->withoutExceptionHandling();

        Bus::fake();

        // Perform create post...
        $response1 = $this->get('/create-post-job');
        $response1->assertStatus(200);

        /**
         * Bus Fake -  inspect which jobs the application attempted to dispatch
         */
        // Assert that a job was dispatched...
        // Bus::assertDispatched(ProcessPost::class);

        /**
         * Bus Fake -  assert that a job was dispatched that passes a given "truth test"
         */
        // Bus::assertDispatched(function (ProcessPost $job) {
            // dd($job);
            // return $job->post->title == 'Title 2';
        // });

        /**
         * Bus Fake - Job Chains
         */
        // Bus::assertChained([
        //     ProcessPost::class,
        //     ProcessPost::class
        // ]);

        /**
         * Bus Fake - Job Batches
         */
        // Bus::assertBatched(function (PendingBatch $batch) {
        //     dd($batch);
        //     return $batch;
            // return $batch->name == 'import-csv' &&
            //        $batch->jobs->count() === 10;
        // });

        // // Assert a job was not dispatched...
        // Bus::assertNotDispatched(ProcessPost::class);
    }

    /**
     * Faking Specific URLs
     * @test
     * */
    public function check_external_api_success_1()
    {
        $fakeUrl = 'https://i.thatcopy.pw/cat-webp/XwHHC9j.webp';
        $fakeResponseJson = '{
            "id": 41,
            "url": "https://i.thatcopy.pw/cat/XwHHC9j.jpg",
            "webpurl": "https://i.thatcopy.pw/cat-webp/XwHHC9j.webp",
            "x": 46.32,
            "y": 68.84
        }';

        Http::fake([
            'https://thatcopy.pw/catapi/rest/*' => Http::response($fakeResponseJson, 200),

            // Stub a string response for all other endpoints...
            // '*' => Http::response($fakeResponseJson, 200),
        ]);

        $response = $this->get('/get-cat-1?action=get');
        $response->assertStatus(200);
        $response->assertViewHas('img', $fakeUrl);
    }

    /**
     * Faking Specific URLs
     * @test
     * */
    public function check_external_api_failed_1()
    {
        $fakeResponseJson = '{
            "id": 41,
            "url": "https://i.thatcopy.pw/cat/XwHHC9j.jpg",
            "webpurl": "https://i.thatcopy.pw/cat-webp/XwHHC9j.webp",
            "x": 46.32,
            "y": 68.84
        }';

        Http::fake([
            'https://thatcopy.pw/catapi/rest/*' => Http::response($fakeResponseJson, 500)

            // Stub a string response for all other endpoints...
            // '*' => Http::response($fakeResponseJson, 500),
        ]);

        $response = $this->get('/get-cat-1?action=get');
        $response->assertStatus(500);
    }

    /**
     * Faking Response Sequences
     * @test
     * */
    public function check_external_api_success_2()
    {
        $this->withoutExceptionHandling();

        $fakeId = 41;
        $fakeResponseJson = '{
            "id": 41,
            "url": "https://i.thatcopy.pw/cat/XwHHC9j.jpg",
            "webpurl": "https://i.thatcopy.pw/cat-webp/XwHHC9j.webp",
            "x": 46.32,
            "y": 68.84
        }';

        $fakeUrl = 'https://i.thatcopy.pw/cat-webp/XiwHHC9j.webp';

        // Http::fake([
        //     // Stub a series of responses for GitHub endpoints...
        //     'https://thatcopy.pw/catapi/rest/*' => Http::sequence()
        //                                                     ->push($fakeResponseJson, 200)
        //                                                     ->push(['webpurl' => 'https://i.thatcopy.pw/cat-webp/XiwHHC9j.webp'], 200)
        //                                                     ->pushStatus(404),
        //                                                     ->whenEmpty(Http::response()),
        // ]);

        Http::fakeSequence()
                ->push($fakeResponseJson, 200)
                ->push(['webpurl' => 'https://i.thatcopy.pw/cat-webp/XiwHHC9j.webp'], 200);

        $response1 = $this->get('/get-cat-2?action=get');
        $response1->assertStatus(200);
        $response1->assertViewHas('id', $fakeId);
        $response2 = $this->get('/get-cat-2?action=get');
        $response2->assertStatus(200);
        $response2->assertViewHas('webpurl', $fakeUrl);
    }

    /**
     * Inspecting Requests
     * @test
     * */
    public function check_external_api_success_3()
    {
        Http::fake();

        Http::withHeaders([
                'X-First' => 'foo',
            ])->post('http://example.com/users', [
                'name' => 'Taylor',
                'role' => 'Developer',
            ]);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-First', 'foo') &&
                   $request->url() == 'http://example.com/users' &&
                   $request['name'] == 'Taylor' &&
                   $request['role'] == 'Developer';
        });
    }

    /**
     * Check Match Response API
     * @test
     * */
    public function check_external_api_success_4()
    {
        $response = $this->get('/get-cat-4?action=get');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJsonStructure([
            'id',
            'url',
            'webpurl',
            'x',
            'y'
        ]);
    }
}
