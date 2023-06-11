<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(20);

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            $users = collect($data)->map(function($input){
                $user = new User();
                $user->name = $input['name'];
                $user->email = $input['email'];
                $user->setPassword($input['password']);
                $user->unit_id = $input['unit_id'];
                $user->join_date = $input['join_date'];

                $user->save();

                $user->jabatan()->sync([...array_column($input['jabatan'], 'id')]);
                $user->refresh();
                $user->load('jabatan');
                return $user;
            });

            DB::commit();

            return response()->json([
                'data' => $users->toArray()
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $pegawai)
    {
        $pegawai->load('jabatan');

        return response()->json([
            'data' => [
                $pegawai->toArray()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $pegawai)
    {
        $data = $request->data[0];
        $pegawai->name = $data['name'];
        $pegawai->email = $data['email'];
        if(isset($data['password'])){
            $pegawai->setPassword($data['password']);
        }
        $pegawai->unit_id = $data['unit_id'];
        $pegawai->join_date = $data['join_date'];
        $pegawai->save();

        $pegawai->jabatan()->sync([...array_column($data['jabatan'], 'id')]);

        return $this->show($pegawai);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $pegawai)
    {
        $pegawai->delete();

        return response()->noContent();
    }
}
