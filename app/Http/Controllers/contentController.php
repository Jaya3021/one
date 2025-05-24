<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class contentController extends Controller
{

     public function index()
    {
        return view('contents');
    }

public function contents(Request $request)
{
    $query = "SELECT id, title, description, vimeo_uri, vimeo_link, embed_html, trailer_path, 
                     thumbnail_path, cast, created_at, updated_at, user_id 
              FROM videos 
              WHERE 1";

    // Filters
    $conditions = [];
    $bindings = [];

    if ($request->filled('title')) {
        $conditions[] = "title LIKE ?";
        $bindings[] = '%' . $request->input('title') . '%';
    }

    if ($request->filled('user_id')) {
        $conditions[] = "user_id = ?";
        $bindings[] = $request->input('user_id');
    }

    if ($request->filled('cast')) {
        $conditions[] = "cast LIKE ?";
        $bindings[] = '%' . $request->input('cast') . '%';
    }

    if ($request->filled('created_from') && $request->filled('created_to')) {
        $conditions[] = "created_at BETWEEN ? AND ?";
        $bindings[] = $request->input('created_from');
        $bindings[] = $request->input('created_to');
    }

    if (!empty($conditions)) {
        $query .= ' AND ' . implode(' AND ', $conditions);
    }

    // Pagination (optional)
    $perPage = $request->input('per_page', 10);
    $page = $request->input('page', 1);
    $offset = ($page - 1) * $perPage;

    $query .= " LIMIT $perPage OFFSET $offset";

    $videos = DB::select($query, $bindings);

    return response()->json([
        'data' => $videos,
        'current_page' => $page,
        'per_page' => $perPage
    ]);
}

    
}
