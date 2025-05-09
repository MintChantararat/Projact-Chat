<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $firebase->createDatabase();
    }

    public function index()
{
    $rawPosts = $this->database->getReference('posts')->getValue();

    if (!is_array($rawPosts)) {
        $rawPosts = [];
    }

    $employees = $this->database->getReference('employee')->getValue() ?? [];

    $pinnedPosts = [];
    $normalPosts = [];

    foreach ($rawPosts as $employeePushId => $postGroup) {
        foreach ($postGroup as $postId => $postData) {
            $images = [];
            foreach ($postData as $key => $value) {
                if (Str::startsWith($key, 'images')) {
                    $images[$key] = $value;
                }
            }

            $post = array_merge([
                'employee_push_id' => $employeePushId,
                'post_id' => $postId,
                'message' => $postData['message'] ?? '',
                'timestamp' => $postData['timestamp'] ?? '',
                'pinned' => $postData['pinned'] ?? false,
            ], $images);

            if ($post['pinned']) {
                $pinnedPosts[] = $post;
            } else {
                $normalPosts[] = $post;
            }
        }
    }

    // เรียงเวลาใหม่ล่าสุดอยู่บน
    usort($pinnedPosts, fn($a, $b) => strtotime($b['timestamp']) <=> strtotime($a['timestamp']));
    usort($normalPosts, fn($a, $b) => strtotime($b['timestamp']) <=> strtotime($a['timestamp']));

    return view('home', [
        'pinnedPosts' => $pinnedPosts,
        'normalPosts' => $normalPosts,
        'employees' => $employees,
    ]);
}




public function store(Request $request)
{
    if (session('role') !== 'admin') {
        return back()->with('error', 'เฉพาะผู้ดูแลระบบเท่านั้นที่สามารถโพสต์ได้');
    }

    $request->validate([
        'message' => 'required|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    $employeePushId = session('firebase_uid');

    if (!$employeePushId) {
        return back()->with('error', 'ไม่พบข้อมูลพนักงานในระบบ');
    }

    $imageData = [];
    if ($request->hasFile('images')) {
        $index = 1;
        foreach ($request->file('images') as $image) {
            $path = $image->store('posts_images', 'public');
            $url = "/storage/posts_images/" . basename($path);
            $imageData["images{$index}"] = $url;
            $index++;
        }
    }

    $postData = array_merge([
        'message' => $request->input('message'),
        'timestamp' => now()->toDateTimeString(),
    ], $imageData);

    $this->database->getReference("posts/{$employeePushId}")->push($postData);

    return redirect()->route('home')->with('success', 'โพสต์สำเร็จ');
}



public function togglePin(Request $request)
{
    $postId = $request->input('post_id');
    $employeePushId = $request->input('employee_push_id');
    $pinned = $request->input('pinned');

    if (!$postId || !$employeePushId) {
        return response()->json(['success' => false]);
    }

    $this->database
        ->getReference("posts/{$employeePushId}/{$postId}/pinned")
        ->set($pinned);

    return response()->json(['success' => true]);
}




public function deletePost(Request $request)
{
    $employeePushId = $request->input('employee_push_id');
    $postId = $request->input('post_id');

    if (session('firebase_uid') !== $employeePushId) {
        return back()->with('error', 'คุณไม่มีสิทธิ์ลบโพสต์นี้');
    }

    $this->database
        ->getReference("posts/{$employeePushId}/{$postId}")
        ->remove();

    return redirect()->route('home')->with('success', 'ลบโพสต์เรียบร้อยแล้ว');
}


}
