<?php

namespace App\Processors;

use App\Repositories\SemesterRepository;
use Illuminate\Database\DatabaseManager;
use Throwable;
use DomainException;

class SemesterProcessor extends BaseProcessor
{
    public function __construct(SemesterRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    /**
     *
     * @param array $data
     * @throws Throwable
     */
    public function create(array $data)
    {
        // --> 1
        if ($this->repo->checkSemesterDateOverlapping($data)) {
            throw new DomainException('Semester dates overlap with existing semesters.');
        }

        //--> 2
        return $this->db->transaction(fn() => $this->repo->create($data));
    }

    /**
     * @param array $data
     * @param int $id
     * @throws Throwable
     */
    public function update(int $id, array $data)
    {
        return $this->db->transaction(fn() => $this->repo->update($id, $data));
    }
    public function delete(int $id): void
    {
        $this->db->transaction(fn() => $this->repo->delete($id));

    }


}
