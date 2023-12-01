<?php

namespace App\Http\Controllers;

use App\Models\ApiResponse;
use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function show($petId)
    {
        try {

            $pet = Pet::with('category', 'tags')->findOrFail($petId);
            $formattedTags = $pet->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            });

            $data = [
                'data' => [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'photoUrls' => $pet->photoUrls,
                    'status' => $pet->status,
                    'category' => $pet->category,
                    'tags' => $formattedTags,
                ],
            ];

            return ApiResponse::success($data);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(404, 'Pet not found');
        } catch (\Exception $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }

    public function findByStatus(Request $request)
    {
        try {
            $request->validate([
                'status' => 'in:available,pending,sold',
            ]);

            $status = $request->input('status');

            $pets = Pet::with('category', 'tags')->where('status', $status)->get();

            $formattedPets = $pets->map(function ($pet) {
                $formattedTags = $pet->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ];
                });

                return [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'photoUrls' => $pet->photoUrls,
                    'status' => $pet->status,
                    'category' => $pet->category,
                    'tags' => $formattedTags,
                ];
            });

            $data = ['data' => $formattedPets];

            return ApiResponse::success($data);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error(405, $e->validator->errors()->first());
        } catch (\Exception $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }

    public function put(Request $request)
    {
        try {
            $request->validate([
                'name' => 'string|max:255',
                'photoUrls' => 'array',
                'photoUrls.*' => 'string',
                'status' => 'in:available,pending,sold',
                'category.id' => 'integer|exists:categories,id',
                'tags' => 'array',
                'tags.*.id' => 'exists:tags,id',
            ]);

            $pet = Pet::findOrFail($request->input('id'));

            $pet->update([
                'name' => $request->input('name'),
                'photoUrls' => $request->input('photoUrls'),
                'status' => $request->input('status'),
                'category_id' => $request->input('category.id'),
            ]);

            if ($request->has('tags')) {

                $tagIds = collect($request->input('tags'))->pluck('id');
                $existingTags = Tag::whereIn('id', $tagIds)->pluck('id'); //sprawdzenie istnienia tagów w tabeli tags przed synchronizacją

                if (count($tagIds) !== count($existingTags)) {
                    return ApiResponse::error(422, 'One or more tags do not exist in the tags table.');
                }
                $pet->tags()->sync($tagIds);
            }

            return ApiResponse::success('Pet updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(404, 'Pet not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error(405, $e->validator->errors()->first());
        } catch (\Exception $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }

    public function update(Request $request, $petId)
    {
        try {
            $pet = Pet::findOrFail($petId);

            $request->validate([
                'name' => 'string|max:255',
                'status' => 'in:available,pending,sold',

            ]);

            $pet->update([
                'name' => $request->input('name'),
                'status' => $request->input('status'),

            ]);

            return ApiResponse::success('Pet updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(404, 'Pet not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error(405, $e->validator->errors()->first());
        } catch (\Exception $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'string|max:255',
                'photoUrls' => 'array',
                'photoUrls.*' => 'string',
                'status' => 'in:available,pending,sold',
                'category.id' => 'integer|exists:categories,id',
                'tags' => 'array',
                'tags.*.id' => 'exists:tags,id',
            ]);

            $pet = Pet::create([
                'name' => $request->input('name'),
                'photoUrls' => $request->input('photoUrls'),
                'status' => $request->input('status'),
                'category_id' => $request->input('category.id')
            ]);

            if ($request->has('tags')) {
                $tagIds = collect($request->input('tags'))->pluck('id');
                $existingTags = Tag::whereIn('id', $tagIds)->pluck('id'); //sprawdzenie istnienia tagów w tabeli tags przed synchronizacją

                if (count($tagIds) !== count($existingTags)) {
                    return ApiResponse::error(422, 'One or more tags do not exist in the tags table.');
                }

                $pet->tags()->sync($tagIds);
            }

            return ApiResponse::success('Pet created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error(405, $e->validator->errors()->first());
        } catch (\Exception $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }
    public function delete($petId)
    {
        try {
            $pet = Pet::findOrFail($petId);

            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();

                if ($user->api_key === request()->header('api_key')) {
                    $pet->delete();

                    return ApiResponse::success('Pet deleted successfully.');
                }
            }

            return ApiResponse::error(401, 'Unauthorized.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(404, 'Pet not found.');
        } catch (\Exception $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }

    public function uploadImage(Request $request, $petId)
    {
        try {
            $pet = Pet::findOrFail($petId);

            $request->validate([
                'file' => 'file|mimes:jpeg,jpg,png,gif',
                'additionalMetaData' => 'string'
            ]);

            $file = $request->file('file');
            $path = $file->store('public/pet_images');
            $fileName = $request->input('additionalMetaData');

            $oldPhotos = $pet->photoUrls;


            $newPhoto = [
                $fileName => Storage::url($path),
            ];

            $oldPhotos[] = $newPhoto;

            $pet->update(['photoUrls' => $oldPhotos]);


            return ApiResponse::success('Image uploaded successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(404, 'Pet not found.');
        } catch (\Exception $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }
}
