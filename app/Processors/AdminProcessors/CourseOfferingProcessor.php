<?php

namespace App\Processors\AdminProcessors;


use App\Processors\BaseProcessor;
use App\Repositories\CourseOfferingRepository;
use Exception;
use Illuminate\Database\DatabaseManager;

class CourseOfferingProcessor extends BaseProcessor
{
    public function __construct(CourseOfferingRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }
    public function listBySemester(int $semesterId)
    {
        return $this->repo->findBySemester($semesterId);
    }


}
