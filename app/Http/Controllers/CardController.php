<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CardService;

class CardController extends Controller
{
    //
    private $cardService;
    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }
    public function index()
    {
        return $this->cardService->getCards();       
    }
    public function show($id)
    {
        return $this->cardService->getCard($id);
    }
    public function store(Request $request, $id)
    {
        return $this->cardService->createCard($request, $id);
    }
    public function update(Request $request, $id)
    {
        return $this->cardService->updateCard($request, $id);
    }
    public function filter(Request $request)
    {
        return $this->cardService->filterCards($request);
    }
    public function destroy($id)
    {
        return $this->cardService->deleteCard($id);
    }
}
