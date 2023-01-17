<?php


namespace App\Services;


use App\Models\Column;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ColumnCollection;
use App\Http\Resources\ColumnResource;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ColumnService
{
    public function createColumn(Request $request)
    {
       $data= $request->validate([
            'title'=>'required',
        ]);
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $user = $token->tokenable;
        $data['user_id'] = $user->id;
        $lease =Column::create($data);
        return (new ColumnResource($lease))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function getColumns()
    {
        $token = PersonalAccessToken::findToken(request()->bearerToken());
        $user = $token->tokenable;
        $columns = Column::where('user_id', $user->id)->where('is_deleted', '0')->get();
        return (new ColumnCollection($columns))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function getColumn (Column $column)
    {
        return (new ColumnResource($column))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateColumn(Request $request, Column $id)
    {
        $column = new ColumnResource($id);
        $column -> update($request->all());
        return ($column)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function deleteColumn(Column $id)
    {
        $id->is_deleted = '1';
        $id->save();
        //delete all cards in column
        $cards = $id->cards;
        if ($cards->isEmpty()) {
            return ($id)->response()->setStatusCode(Response::HTTP_OK);
        }
        foreach ($cards as $card) {
            $card->is_deleted = '1';
            $card->save();
        }
        //return response()->json(['message' => 'Column deleted successfully']);
        return ($id)->response()->setStatusCode(Response::HTTP_OK);

    }
}