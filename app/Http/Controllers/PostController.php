<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function index()
    {
        $post = Post::latest()->first();
        return new PostResource($post);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $images = $data['images'];
        unset($data['images']);

        $post = Post::firstOrCreate($data);

        foreach ($images as $image) {
            $name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $previewName = 'prev_' . $name;
            $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);

            Image::create([
                'path' => $filePath,
                'url' => url('/storage/' . $filePath),
                'preview_url' => url('/storage/images/' . $previewName),
                'post_id' => $post->id,
            ]);
            \Intervention\Image\Facades\Image::make($image)->fit(100, 100)->save(storage_path('app/public/images/' . $previewName));
        }
        return response()->json(['message' => 'success']);
    }

    public function update(UpdateRequest $request, Post $post)
    {
        $data = $request->validated();
        $images = isset($data['images']) ? $data['images'] : null;
        $imageIdsForDelete = isset($data['image_ids_for_delete']) ? $data['image_ids_for_delete'] : null;
        $imageUrlsForDelete = isset($data['image_urls_for_delete']) ? $data['image_urls_for_delete'] : null;

        unset($data['images'], $data['image_ids_for_delete'], $data['image_urls_for_delete']);
        $post->update($data);

        $currentImages = $post->images;
        if ($imageIdsForDelete) {
            foreach ($currentImages as $currentImage) {
                if (in_array($currentImage->id, $imageIdsForDelete)) {
                    Storage::disk('public')->delete($currentImage->path);
                    Storage::disk('public')->delete(str_replace('images/', 'images/prev_', $currentImage->path));
                    $currentImage->delete();
                }
            }
        }

        if ($imageUrlsForDelete) {
            foreach ($imageUrlsForDelete as $imageUrlForDelete) {
                $removeStr = $request->root() . '/storage/';
                $path = str_replace($removeStr, '', $imageUrlForDelete);
                Storage::disk('public')->delete($path);
            }
        }

        $post = Post::firstOrCreate($data);
        if ($images) {
            foreach ($images as $image) {
                $name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $previewName = 'prev_' . $name;
                $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);

                Image::create([
                    'path' => $filePath,
                    'url' => url('/storage/' . $filePath),
                    'preview_url' => url('/storage/images/' . $previewName),
                    'post_id' => $post->id,
                ]);
                \Intervention\Image\Facades\Image::make($image)->fit(100, 100)->save(storage_path('app/public/images/' . $previewName));
            }
        }
        return response()->json(['message' => 'success']);
    }
}
