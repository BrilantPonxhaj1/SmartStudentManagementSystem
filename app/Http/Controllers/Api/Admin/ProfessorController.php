<?php
namespace App\Http\Controllers\Api\Admin;

use App\Processors\ProfessorProcessor;
use App\Http\Requests\StoreProfessorRequest;
use App\Http\Requests\UpdateProfessorRequest;

class ProfessorController extends BaseAdminController
{
    public function __construct(protected ProfessorProcessor $service) {}

    public function index()
    {
        return ProfessorResource::collection($this->service->list());
    }

    public function store(StoreProfessorRequest $request)
    {
        $professor = $this->service->create($request->validated());
        return new ProfessorResource($professor);
    }

    public function show($id)
    {
        return new ProfessorResource($this->service->get($id));
    }

    public function update(UpdateProfessorRequest $request, $id)
    {
        $professor = $this->service->update($id, $request->validated());
        return new ProfessorResource($professor);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->noContent();
    }
}
