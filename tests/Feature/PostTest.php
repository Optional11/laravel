<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_no_blogposts_when_nothing_in_db()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No Posts Found.');
    }

    public function test_see_one_blogpost_where_there_is_one()
    {
        //arrange part
        $post = $this->create_dummy_blog_post();

        // act part
        $response = $this->get('/posts');

        // assert part
        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title',
        ]);
    }

    public function test_store_valid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters',
        ];

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'The blog post was created');
    }

    public function test_store_fail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x',
        ];

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        // dd($messages->getMessages());
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function test_update_valid()
    {
        //arrange part
        $post = $this->create_dummy_blog_post();

        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        $params = [
            'title' => 'A new named title',
            'content' => 'Content was chnaged',
        ];

        $this->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog Post was updated');

        $this->assertDatabaseMissing('blog_posts', $post->getAttributes());
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new named title',
            'content' => 'Content was chnaged',
        ]);
    }

    public function test_delete()
    {
        $post = $this->create_dummy_blog_post();
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        $this->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog Post was deleted');
        $this->assertDatabaseMissing('blog_posts', $post->getAttributes());
    }

    private function create_dummy_blog_post(): BlogPost
    {
        $post = new BlogPost();
        $post->title = 'New title';
        $post->content = 'Content of the blog post';
        $post->save();

        return $post;
    }
}
