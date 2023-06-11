<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserAccess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['unit', 'jabatan'])->paginate(20);

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

    public function summary(Request $request) {
        $validated = $request->validate([
            'date_start' => 'date_format:Y-m-d|nullable',
            'date_end' => 'date_format:Y-m-d|nullable'
        ]);

        $query = User::query();

        if(isset($validated['date_start']) && isset($validated['date_end'])){
            $query->whereBetween('join_date', [
                $validated['date_start'], $validated['date_end']
            ]);
        }

        return response()->json([
            'data' => [
                'total' => $query->count('id'),
            ]
        ]);
    }



    public function summaryLogin(Request $request) {
        $validated = $request->validate([
            'date_start' => 'date_format:Y-m-d|nullable',
            'date_end' => 'date_format:Y-m-d|nullable'
        ]);

        $query = UserAccess::query();

        if(isset($validated['date_start']) && isset($validated['date_end'])){
            $query->whereBetween('created_at', [
                $validated['date_start'], $validated['date_end']
            ]);
        }

        return response()->json([
            'data' => [
                'total' => $query->count('id'),
            ]
        ]);
    }

    public function summaryTopLogin(Request $request) {
        $validated = $request->validate([
            'date_start' => 'date_format:Y-m-d|nullable',
            'date_end' => 'date_format:Y-m-d|nullable'
        ]);

        $query = UserAccess::with('user')->selectRaw('count(user_id) as login_count, user_id')
            ->where('activity', 'login')->havingRaw('login_count > ?', [25])
            ->orderBy('login_count', 'desc')
            ->groupBy('user_id');

        if(isset($validated['date_start']) && isset($validated['date_end'])){
            $query->whereBetween('created_at', [
                $validated['date_start'], $validated['date_end']
            ]);
        }


        $data = $query->get();
        $data = $data->map(function($dt){
            $lastLogin = UserAccess::where('user_id', $dt->user->id)->latest()->first();

            $x = $dt->toArray();
            $x['last_login_time'] = $lastLogin->created_at;
            return $x;
        })->toArray();

        return response()->json([
            'data' => $data
        ]);
    }
}
