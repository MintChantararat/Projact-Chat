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
        $aboutRef = $this->database->getReference('about');
        $existingData = $aboutRef->getValue() ?? [];

        $data = $request->only(['company_name', 'established_at', 'email', 'phone', 'address']);

        // ✅ ใช้รูปเดิมถ้าไม่มีการอัปโหลดใหม่
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('about', 'public');
            $data['photo'] = '/storage/' . $path;
        } else {
            $data['photo'] = $existingData['photo'] ?? null;
        }

        $aboutRef->update($data); // ✅ ใช้ update แทน set

        return redirect()->route('about.show')->with('status', 'บันทึกข้อมูลแล้ว');
    }

}
