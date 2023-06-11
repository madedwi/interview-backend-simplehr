<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorejabatanRequest;
use App\Http\Requests\UpdatejabatanRequest;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginator = Jabatan::paginate(30);

        return response()->json($paginator);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorejabatanRequest $request)
    {
        $data = $request->data;
        $data = collect($data)->map(function($input) {
            $jabatan = new Jabatan(['name' => $input['name']]);
            $jabatan->save();
            return $jabatan;
        });

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        return response()->json([
            'data' => $jabatan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatejabatanRequest $request, Jabatan $jabatan)
    {
        $data = $request->data[0];
        $jabatan->name = $data['name'];
        $jabatan->save();

        return $this->show($jabatan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return response()->noContent();
    }

    public function summary() {

        $query = Jabatan::query();

        return response()->json([
            'data' => [
                'total' => $query->count('id'),
            ]
        ]);
    }
}
