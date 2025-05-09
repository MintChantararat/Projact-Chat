<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;

class AboutController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();
    }

    public function show()
    {
        $about = $this->database->getReference('about')->getValue();
        $members = $this->database->getReference('employee')->getValue() ?? [];
        return view('about', [
            'about' => $about,
            'memberCount' => count($members),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->only(['company_name', 'established_at', 'email', 'phone', 'address']);

        // ✅ อัปโหลดรูปภาพ
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('about', 'public');
            $data['photo'] = '/storage/' . $path;
        }

        $this->database->getReference('about')->set($data);
        return redirect()->route('about.show')->with('status', 'บันทึกข้อมูลแล้ว');
    }
}
