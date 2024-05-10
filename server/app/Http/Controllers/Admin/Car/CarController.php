<?php

namespace App\Http\Controllers\Admin\Car;

use App\Enums\CarVerify;
use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Verifies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    private $car;
    private $verify;

    public function __construct(Cars $car, Verifies $verify)
    {
        $this->car = $car;
        $this->verify = $verify;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->car->with('brand', 'figure', 'gear', 'color', 'user')->orderBy('created_at', 'desc')->get();
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $car = $this->car->create($params);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = $this->car->where('id', $id)->first();

        if (!$car) {
            return response()->json([
                'message' => 'car not found'
            ], 404);
        }

        return response()->json($car);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $params = $request->all();

        $car = $this->car->where('id', $id)->first();

        if (!$car) {
            return response()->json([
                'message' => 'car not found'
            ], 404);
        }

        $car->update($params);

        return response()->json($car);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = $this->car->where('id', $id)->first();
        if (!$car) {
            return response()->json([
                'message' => 'car not found'
            ], 404);
        }
        $car->delete();
        return response()->json($car, 200);
    }

    public function getVerify($id)
    {
        $verify = $this->verify->where('car_id', $id)->first();

        if (!$verify) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        return $verify;
    }

    public function adminVerify(Request $request, string $id)
    {
        $car = $this->car
            ->where('id', $id)
            ->first();

        if (!$car) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        $params = $request->all();

        $verify = $this->verify->where('car_id', $car->id)->first();

        if (!$verify) {
            // create verify record
            $verify = $this->verify->create([
                'user_id' => Auth::id(),
                'car_id' => $car->id,
                'body_condition' => $params['body_condition'],
                'chassis_condition' => $params['chassis_condition'],
                'engine_condition' => $params['engine_condition'],
                'interior_condition' => $params['interior_condition'],
                'test_drive' => $params['test_drive'],
                'score' => $params['score'],
                'note' => $params['note'],
            ]);
        } else {
            $this->verify->where('car_id', $car->id)->update($params);
        }

        $car->verify = CarVerify::Verified();

        $car->save();

        return $car;
    }
}
