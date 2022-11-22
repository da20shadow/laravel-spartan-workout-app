<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Exercises;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ExercisesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();
        //TODO: return only user exercises in last 30 days
        $exercises = Exercises::all()->where('user_id','=',$userId);

        if (count($exercises) < 1){
            return response()->json(['message' => 'No exercises found!'],404);
        }

        $exercisesList = [];
        foreach ($exercises as $workout){

            $date = date('Y-m-d', strtotime($workout['created_at']));
            array_push($exercisesList,[
                'id' => $workout['id'],
                'user_id' => $workout['user_id'],
                'push_ups' => $workout['push_ups'],
                'sit_ups' => $workout['sit_ups'],
                'bench_dips' => $workout['bench_dips'],
                'squats' => $workout['squats'],
                'pull_ups' => $workout['pull_ups'],
                'hammer_curl' => $workout['hammer_curl'],
                'barbel_curl' => $workout['barbel_curl'],
                'created_at' => $date
            ]);
        }
        return response()->json($exercisesList);
    }

    public function show(StorePostRequest $request): JsonResponse
    {
        //TODO: return only user exercises from today
        $request = $request->all();

        $exercises = Exercises::all();

        return response()->json([
            'exercises' => $exercises,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest  $request): JsonResponse
    {
        //{workoutType: "push_ups", reps: 1}
        $user_inputs = $request->all();
        $workout = [$user_inputs['workoutType'] => $user_inputs['reps']];


        $sql = "SELECT * FROM exercises
            WHERE DAY(created_at) =  DAY(NOW())
            AND MONTH(created_at) = MONTH(NOW())
            AND YEAR(created_at) = Year(NOW())
            AND user_id = ?";

        $user_id = auth()->user()->getAuthIdentifier();
        $todayWorkout = DB::select($sql,[$user_id]);

        if ($todayWorkout){
            $w = $user_inputs['workoutType'];
            $type = $user_inputs['workoutType'];
            $totalReps = $todayWorkout[0]->$w + $user_inputs['reps'];
            $date = date('Y-m-d');

            DB::table('exercises')
                ->where('user_id',$user_id)
                ->whereDate('created_at',$date)
                ->update([$type => $totalReps]);

            return response()->json([
                'message' => 'Successfully added '. $totalReps .' reps!',
            ],201);
        }
        $workout['user_id'] = $user_id;

        $exercises = Exercises::create($workout);

        return response()->json([
            'status' => true,
            'message' => 'Added Successfully!',
            'exercises' => $exercises
        ],201);
    }

    /**
     * Display the specified resource.
     * @param Exercises $exercises
     * @return JsonResponse
     */
    public function showToday(Exercises $exercises): JsonResponse
    {
        $user_id = auth()->user()->getAuthIdentifier();
        $sql = "SELECT * FROM exercises
            WHERE DAY(created_at) =  DAY(NOW())
            AND MONTH(created_at) = MONTH(NOW())
            AND YEAR(created_at) = Year(NOW())
            AND user_id = ? LIMIT 1";

        $result = DB::select($sql,[$user_id]);

        if (!$result){
            return response()->json([
                'id' => $user_id,
                'message' => "No Exercises today!",
            ],404);
        }

        return response()->json($result[0]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Exercises $exercises
     * @return \Illuminate\Http\Response
     */
    public function edit(Exercises $exercises)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StorePostRequest $request
     * @param Exercises $exercises
     * @return JsonResponse
     */
    public function update(StorePostRequest $request, Exercises $exercises): JsonResponse
    {
        $exercises->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Today workout updated!',
            'exercises' => $exercises
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Exercises $exercises
     * @return JsonResponse
     */
    public function destroy(Exercises $exercises): JsonResponse
    {
        $exercises->delete();

        return response()->json([
            'status' => true,
            'message' => 'Workout Deleted!'
        ]);
    }
}
