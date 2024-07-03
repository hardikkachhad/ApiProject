<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();

        return response()->json([
            'sucess' => true,
            'errors' => $data,
            'message' => 'All Post Data'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required'
        ]);
        if ($validator->passes()) {
            $post = new Post;

            $post->title = $request->title;
            $post->description = $request->description;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $image->getClientOriginalName();
                $image->move('upload/', $filename);
                $post->image = $filename;
            }
            $post->save();
            return response()->json([
                'status' => true,
                'errors' => $post,
                'message' => 'post created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where(['id' => $id])->get();

        return response()->json([
            'status' => true,
            'message' => 'Show Data Successfully',
            'errors' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'image' => 'required'
        ]);
        if ($validator->passes()) {
            $postupdate = Post::findOrFail($id);
            $postupdate->title = $request->title;
            $postupdate->description = $request->description;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $image->getClientOriginalName();
                $image->move('upload/', $filename);
                $postupdate->image = $filename;
            }
            $postupdate->update(); // Saving the post

            return response()->json([
                'status' => true,
                'message' => 'Post Update sucessFully',
                'errors' => $postupdate
            ]);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Update post Error',
                'errors' => $validator->errors()
            ]);
        }
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deletepost = Post::findOrFail($id);
        $deletepost->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post Delete SucessFully',
            'error' => $deletepost
        ]);
    }
}
