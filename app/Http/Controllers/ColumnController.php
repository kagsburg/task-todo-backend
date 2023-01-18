<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ColumnService;

class ColumnController extends Controller
{
    //
    private $columnService;
    public function __construct(ColumnService $columnService)
    {
        $this->columnService = $columnService;
    }
    public function index()
    {
        return $this->columnService->getColumns();       
    }
    public function show($id)
    {
        return $this->columnService->getColumn($id);
    }
    public function store(Request $request)
    {
        return $this->columnService->createColumn($request);
    }
    public function update(Request $request, $id)
    {
        return $this->columnService->updateColumn($request, $id);
    }
    public function destroy($id)
    {
        return $this->columnService->deleteColumn($id);
    }
}
