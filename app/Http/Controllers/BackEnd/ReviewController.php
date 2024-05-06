<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Review;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index() : JsonResponse
    {
        try {
            $reviews = Review::query()
                ->select(
                    'reviews.id',
                    'reviews.title_movie',
                    'reviews.synopsis',
                    'reviews.review',
                    'reviews.release_date',
                    'reviews.url_poster',
                    'users.name as author'
                )
                ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
                ->orderBy('reviews.release_date', 'desc') // Ordenar primero por fecha de lanzamiento descendente
                ->orderBy('reviews.title_movie', 'asc')
                ->get()
                ->map(function ($movie) {
                    return [
                        'id' => $movie->id,
                        'title_movie' => $movie->title_movie,
                        'synopsis' => $movie->synopsis,
                        'release_date' => $movie->release_date,
                        'url_poster' => Storage::disk('public')->url($movie->url_poster),
                        'author' => $movie->author
                    ];
                });
            return response()->json($reviews, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function show($id) : JsonResponse
    {
        try {
            $review = Review::findOrfail($id);

            $reviewDetails = [
                'id' => $review->id,
                'title' => $review->title_movie,
                'synopsis' => $review->synopsis,
                'review' => $review->review,
                'release_date' => $review->release_date,
                'url_poster' => Storage::disk('public')->url($review->url_poster),
                'writer' => $review->user->name
            ];

            $comments = $review->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'author' => $comment->user->name,
                    'date' => $comment->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json(['review' => $reviewDetails, 'comments' => $comments]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function store(Request $request) : JsonResponse
    {
        try {
            $this->validate($request,
                [
                    'title' => 'required|min:3|max:255',
                    'synopsis' => 'required|max:2000',
                    'review' => 'required|max:2000',
                    'release_date' => 'required|date',
                    'poster' => 'required|file|image|mimes:jpg,jpeg,png,gif,webp',
                    'user_id' => 'required|exists:users,id'
                ],
                [
                    'title.required' => 'The title field is required',
                    'title.min' => 'The title must be at least 3 characters',
                    'title.max' => 'The title may not be greater than 255 characters',
                    'synopsis.required' => 'The synopsis field is required',
                    'synopsis.max' => 'The synopsis may not be greater than 255 characters',
                    'review.required' => 'The review field is required',
                    'review.max' => 'The review may not be greater than 1000 characters',
                    'release_date.required' => 'The release date field is required',
                    'release_date.date' => 'The release date is not a valid date',
                    'poster.required' => 'The url poster field is required',
                    'poster.file' => 'The url poster must be a file',
                    'poster.image' => 'The url poster must be an image',
                    'poster.mimes' => 'The url poster must be a file of type: jpg, jpeg, png, gif, webp',
                    'user_id.required' => 'The user id field is required',
                    'user_id.exists' => 'The user id does not exist'
                ]
            );

            DB::beginTransaction();

            if ($request->hasFile('poster')) {

                $directoryName = 'images/events/';

                $image = $request->file('poster');

                $imageName = time() . '.' . $image->extension();


                if (!Storage::disk('public')->exists($directoryName)) {
                    Storage::disk('public')->makeDirectory($directoryName);
                }

                Storage::disk('public')->putFileAs($directoryName, $image, $imageName);
            } else {
                throw new Exception('The poster field is required');
            }

            Review::create([
                'title_movie' => $request->title,
                'synopsis' => $request->synopsis,
                'review' => $request->review,
                'release_date' => $request->release_date,
                'url_poster' => $directoryName . $imageName,
                'user_id' => $request->user_id
            ]);
            DB::commit();

            return response()->json(['message' => 'Review created successfully']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addComment(Request $request) : JsonResponse
    {
        try {
            $this->validate($request,
                [
                    'comment' => 'required|max:500',
                    'user_id' => 'required|exists:users,id',
                    'review_id' => 'required|exists:reviews,id'
                ],
                [
                    'comment.required' => 'The comment field is required',
                    'comment.max' => 'The comment may not be greater than 255 characters',
                    'user_id.required' => 'The user id field is required',
                    'user_id.exists' => 'The user id does not exist',
                    'review_id.required' => 'The review id field is required',
                    'review_id.exists' => 'The review id does not exist'
                ]
            );

            DB::beginTransaction();

            Comment::create([
                'content' => $request->comment,
                'user_id' => $request->user_id,
                'review_id' => $request->review_id
            ]);


            DB::commit();

            return response()->json(['message' => 'Comment added successfully']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
