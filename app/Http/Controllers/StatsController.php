<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;

class StatsController extends Controller
{
    public function stats(){
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $usersWithZeroPosts = User::doesntHave('posts')->count();

        return response()->json([
            'total_users' => $totalUsers,
            'total_posts' => $totalPosts,
            'users_with_zero_posts' => $usersWithZeroPosts,
        ]);
    }
}
