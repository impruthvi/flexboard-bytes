<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Display the leaderboard with ranked users.
     */
    public function index(): View
    {
        $users = User::query()
            ->select(['id', 'name', 'username', 'avatar', 'points', 'current_streak', 'longest_streak'])
            ->orderByDesc('points')
            ->limit(50)
            ->get()
            ->map(function ($user, $index) {
                $user->rank = $index + 1;

                return $user;
            });

        $currentUserRank = null;
        $currentUser = Auth::user();

        if ($currentUser) {
            $currentUserRank = User::where('points', '>', $currentUser->points)->count() + 1;
        }

        return view('leaderboard.index', compact('users', 'currentUserRank'));
    }
}
