<?php


namespace App\Services;


use App\Models\Card;
use App\Http\Resources\CardCollection;
use App\Http\Resources\CardResource;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\DbDumper\Databases\MySql;
class CardService
{
    public function createCard(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $user = $token->tokenable;
        $lease =Card::create([
            'title' => $request->title,
            'description' => $request->description,
            'column_id' => $id,
        ]);
        return (new CardResource($lease))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function getCards()
    {
        $token = PersonalAccessToken::findToken(request()->bearerToken());
        $user = $token->tokenable;
        $columns = Card::where('user_id', $user->id)->where('is_deleted', '0')->get();
        return (new CardCollection($columns))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function filterCards(Request $request)    
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([''], 401);
        };
        if ($request->date){
            $columns = Card::where('is_deleted', $request->status)->where('created_at', '>=', $request->date)->get();
            return (new CardCollection($columns))->response()->setStatusCode(Response::HTTP_OK);
        }
        $columns = Card::where('is_deleted', $request->status)->get();
        return (new CardCollection($columns))->response()->setStatusCode(Response::HTTP_OK);
        
    }
    public function getCard ($id)
    {
        // 
        $single = Card::where('id', $id)->where('is_deleted', '0')->first();
        if (!$single) {
            return response()->json(['error' => 'Card not found'], 404);
        }
        return (new CardResource($single))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateCard(Request $request, $id)
    {
        
        $column = Card::where('id', $id)->where('is_deleted', '0')->first();
        if (!$column) {
            return response()->json(['error' => 'Card not found'], 404);
        }
        $column->title = $request->title;
        $column->description = $request->description;
        $column->column_id = $request->column_id;
        $column->save();
        return (new CardResource($column))->response()->setStatusCode(Response::HTTP_OK);
    }
    public function deleteCard($id)
    {
        $id = Card::where('id', $id)->where('is_deleted', '0')->first();
        if (!$id) {
            return response()->json(['error' => 'Card not found'], 404);
        }
        $id->is_deleted = '1';
        $id->save();
        return response()->json(['message' => 'Card deleted successfully']);

    }
}