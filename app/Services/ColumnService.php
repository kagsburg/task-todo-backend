<?php


namespace App\Services;


use App\Models\Column;
use App\Http\Resources\ColumnCollection;
use App\Http\Resources\ColumnResource;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ColumnService
{
    public function createColumn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $user = $token->tokenable;
        $lease =Column::create([
            'title' => $request->title,
            'user_id' => $user->id,
        ]);
        return (new ColumnResource($lease))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function getColumns()
    {
        $token = PersonalAccessToken::findToken(request()->bearerToken());
        $user = $token->tokenable;
        $columns = Column::where('user_id', $user->id)->where('is_deleted', '0')->get();
        return (new ColumnCollection($columns))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function getColumn ($id)
    {
        // 
        $single = Column::where('id', $id)->where('is_deleted', '0')->first();
        if (!$single) {
            return response()->json(['error' => 'Column not found'], 404);
        }
        return (new ColumnResource($single))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateColumn(Request $request, $id)
    {
        
        $column = Column::where('id', $id)->where('is_deleted', '0')->first();
        if (!$column) {
            return response()->json(['error' => 'Column not found'], 404);
        }
        $column->title = $request->title;
        $column->save();
        return (new ColumnResource($column))->response()->setStatusCode(Response::HTTP_OK);
        // return ($column)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function deleteColumn($id)
    {
        $id = Column::where('id', $id)->where('is_deleted', '0')->first();
        if (!$id) {
            return response()->json(['error' => 'Column not found'], 404);
        }
        $id->is_deleted = '1';
        $id->save();
        //delete all cards in column
        $cards = $id->cards;
        if ($cards->isEmpty()) {
            return response()->json(['message' => 'Column deleted successfully']);
        }
        foreach ($cards as $card) {
            $card->is_deleted = '1';
            $card->save();
        }
        return response()->json(['message' => 'Column deleted successfully']);
        // return ($id)->response()->setStatusCode(Response::HTTP_OK);

    }
}