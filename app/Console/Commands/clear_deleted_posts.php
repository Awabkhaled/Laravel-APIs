<?php

namespace App\Console\Commands;
use \Exception;
use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class clear_deleted_posts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:clear_deleted_posts';

    /**
     * The console command description.
     */
    protected $description = 'force-deletes all softly-deleted posts for more than 30 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $posts = Post::onlyTrashed()
                ->where('deleted_at', '<=', Carbon::now()->subDays(30))
                ->get();
            foreach ($posts as $post) {
                $ImagePath = public_path('uploaded_images/'.basename($post->cover_image));
                unlink($ImagePath);
                $post->forceDelete();
                $this->info('Force deleted post with ID: ' . $post->id);
            }
            $this->info('-> FINISHED DELETING '.count($posts). ' POSTS');
        }
        catch(Exception $e){
            $this->error("An error occurred.". $e->getMessage());
        }
    }
}
