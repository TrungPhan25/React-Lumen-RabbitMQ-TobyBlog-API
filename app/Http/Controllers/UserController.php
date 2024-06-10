<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->all();
            
            $data['password'] = Hash::make($data['password']);
            
            if ($request->hasFile('image')) {
                $file          = $request->file('image');
                $ext           = $file->getClientOriginalExtension();
                $filename      = time().'image.'.$ext;
                $imagePath     = $file->storeAs('images',$filename, 'public');
                $data['image'] = $filename;
            }
            
            $user = User::create($data);

            return response(new UserResource($user) , 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }


    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) 
    {
        $user = User::find($id);
        $imagePath  = route('post.get_image', ['filename' => $user->image]);

        return response()->json([
            'user'       => new UserResource($user),
            'image'      => $imagePath
        ]);
    }


    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $data = $request->all();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $user = User::find($id);
            if ($request->hasFile('image')) {
                $file          = $request->file('image');
                $ext           = $file->getClientOriginalExtension();
                $filename      = time().'image.'.$ext;
                $imagePath     = $file->storeAs('images',$filename, 'public');
                $data['image'] = $filename;
            }
            $user->update($data);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response("", 204);
    }

}
