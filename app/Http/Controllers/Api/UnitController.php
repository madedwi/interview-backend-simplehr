<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginator = Unit::paginate(5);

        return response()->json($paginator);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $data = $request->data;

        $data = collect($data)->map(function($input){
            $unit = new Unit(['name' => $input['name']]);
            $unit->save();
            return $unit;
        });

        return response()->json([
            'data' => $data->toArray()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        return response()->json([
            'data' => [
                $unit->toArray()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $unitInput = $request->data[0];

        $unit->name = $unitInput['name'];
        $unit->save();

        return $this->show($unit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return response()->noContent();
    }
}
