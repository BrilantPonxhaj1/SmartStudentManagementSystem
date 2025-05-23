<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Processors\AdminProcessors\DepartmentProcessor;
use App\Repositories\DepartmentRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class DepartmentProcessorTest extends TestCase
{
    public function testListAllWithRelations(){
        $repo = Mockery::mock(DepartmentRepository::class);
        $repo->shouldReceive('allWithRelations')->once()->andReturn(new Collection(['department1', 'department2']));
        $database = Mockery::mock(DatabaseManager::class);
        $processor = new DepartmentProcessor($repo, $database);
        $this->assertEquals(new Collection(['department1', 'department2']), $processor->list());
    }

    public function testListByUniversityId(){
        $repo = Mockery::mock(DepartmentRepository::class);
        $repo->shouldReceive('listByUniversityId')->once()->with(1)->andReturn(new Collection(['department1', 'department2']));
        $database = Mockery::mock(DatabaseManager::class);
        $processor = new DepartmentProcessor($repo, $database);
        $this->assertEquals(new Collection(['department1', 'department2']), $processor->listByUniversity(1));
    }

    public function testFindWithRelations(){
        $fakeDepartment = new Department();
        $fakeDepartment->id = 1;
        $fakeDepartment->name = 'Fake Department';
        $repo = Mockery::mock(DepartmentRepository::class);
        $repo->shouldReceive('findWithRelations')->once()->with(1)->andReturn($fakeDepartment);
        $database = Mockery::mock(DatabaseManager::class);
        $processor = new DepartmentProcessor($repo, $database);
        $this->assertEquals($fakeDepartment, $processor->get(1));
    }

}
