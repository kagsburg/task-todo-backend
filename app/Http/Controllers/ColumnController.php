<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ColumnService;
use App\Models\Column;

use App\Models\Lease;
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
    public function show(Column $id)
    {
        return $this->columnService->getColumn($id);
    }
    public function store(Request $request)
    {
        return $this->columnService->createColumn( $request);
    }
    public function update(Request $request,Column $id)
    {
        return $this->columnService->updateColumn($request, $id);
    }
    public function destroy(Column $id)
    {
        return $this->columnService->deleteColumn($id);
    }
}
