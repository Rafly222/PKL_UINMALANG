<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function photo($id)
    {
        $presence = Presence::findOrFail($id);

        if (!$presence->photo) {
            abort(404);
        }

        if (str_starts_with($presence->photo, 'data:image') || !str_contains($presence->photo, '/')) {
            $data = explode(',', $presence->photo);
            $image = base64_decode($data[1] ?? $data[0]);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        if (Storage::exists($presence->photo)) {
            $image = Storage::get($presence->photo);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        $fallbackPath = storage_path('app/' . $presence->photo);
        if (file_exists($fallbackPath)) {
            $image = file_get_contents($fallbackPath);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        abort(404);
    }

    public function signature($id)
    {
        $presence = Presence::findOrFail($id);

        if (!$presence->signature) {
            abort(404);
        }

        if (str_starts_with($presence->signature, 'data:image') || !str_contains($presence->signature, '/')) {
            $data = explode(',', $presence->signature);
            $image = base64_decode($data[1] ?? $data[0]);
            return response($image)->header('Content-Type', 'image/png');
        }

        if (Storage::exists($presence->signature)) {
            $image = Storage::get($presence->signature);
            return response($image)->header('Content-Type', 'image/png');
        }

        $fallbackPath = storage_path('app/' . $presence->signature);
        if (file_exists($fallbackPath)) {
            $image = file_get_contents($fallbackPath);
            return response($image)->header('Content-Type', 'image/png');
        }

        abort(404);
    }
}
