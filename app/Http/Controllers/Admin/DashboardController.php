<?php

/**
 * Dashboard Controller
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index(Request $request)
  {

    if (!empty($request->start_date) && !empty($request->end_date)) {
      $announcements = Announcement::where('status', 'published')
        ->where('created_at', '>=', $request->start_date . ' 00:00:00')
        ->where('created_at', '<=', $request->end_date . ' 23:59:59')
        ->orderby('created_at', 'desc')
        ->get();
    } elseif (!empty($request->searchq)) {
      $announcements = Announcement::where('status', 'published')
        ->where('title', 'LIKE', '%' . $request->searchq . '%')->orWhere('content', 'LIKE', '%' . $request->searchq . '%')->orderby('created_at', 'desc')
        ->get();
    } else {
      $announcements = Announcement::where('status', 'published')->orderby('created_at', 'desc')->get();
    }
    return view('admin.dashboard', ['announcements' => $announcements]);
  }
}
