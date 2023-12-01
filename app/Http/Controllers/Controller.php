<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function show(Request $petId)
    {
        try {
            $pet = Pet::with(['category', 'tags'])->findOrFail($petId);

            $response = [
                'id' => $pet->id,
                'category' => [
                    'id' => $pet->category->id,
                    'name' => $pet->category->name,
                ],
                'name' => $pet->name,
                'photoUrls' => json_decode($pet->photoUrls),
                'tags' => $pet->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ];
                }),
                'status' => $pet->status,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Pet not found.'], 404);
        }
    }
}
