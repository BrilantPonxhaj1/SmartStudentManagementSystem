<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\University;
use Illuminate\Foundation\Testing\TestCase;

class DepartmentControllerTest extends FeatureTestCase
{
    public function testReturnsAllDepartments()
    {
        $this->actingAsRole('superadmin');
        $uni = University::create([
            'name' => 'Test University',
            'code' => 'TU',
            'address' => '123 Testing Blvd',
        ]);

        Department::create([
            'name' => 'Physics',
            'code' => 'PHYS',
            'university_id' => $uni->id,
        ]);
        Department::create([
            'name' => 'Math',
            'code' => 'MATH',
            'university_id' => $uni->id,
        ]);
        Department::create([
            'name' => 'Chemistry',
            'code' => 'CHEM',
            'university_id' => $uni->id,
        ]);
        $response = $this->getJson('/api/admin/departments');

        $response->assertOk()
            ->assertJsonCount(3);
    }

    public function testStoreDepartment()
    {
        $this->actingAsRole('superadmin');

        $uni = University::create([
            'name'    => 'Test University',
            'code'    => 'TU',
            'address' => '123 Testing Blvd',
        ]);

        $payload = [
            'name'          => 'Physics',
            'code'          => 'PHYS',
            'university_id' => $uni->id,
        ];

        $response = $this->postJson('/api/admin/departments', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('name', 'Physics')
            ->assertJsonPath('code', 'PHYS')
            ->assertJsonPath('university.id', $uni->id);

        $this->assertDatabaseHas('departments', $payload);
    }
    public function testShowDepartment()
    {
        $this->actingAsRole('superadmin');

        $uni = University::create([
            'name'    => 'Another University',
            'code'    => 'AU',
            'address' => '456 Another St',
        ]);

        $dept = Department::create([
            'name'          => 'Biology',
            'code'          => 'BIOL',
            'university_id' => $uni->id,
        ]);

        $response = $this->getJson("/api/admin/departments/{$dept->id}");

        $response->assertOk()
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.name', 'Biology');
    }

    public function testUpdateDepartment()
    {
        $this->actingAsRole('superadmin');

        $uni = University::create([
            'name'    => 'Update U',
            'code'    => 'UU',
            'address' => '789 Update Rd',
        ]);

        $dept = Department::create([
            'name'          => 'Old Name',
            'code'          => 'OLD',
            'university_id' => $uni->id,
        ]);

        $payload = [
            'name' => 'New Name',
            'code' => 'NEW',
        ];

        $response = $this->putJson("/api/admin/departments/{$dept->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('name', 'New Name')
            ->assertJsonPath('code', 'NEW');

        $this->assertDatabaseHas('departments', [
            'id'   => $dept->id,
            'name' => 'New Name',
            'code' => 'NEW',
        ]);
    }

    public function testDestroyDepartment()
    {
        $this->actingAsRole('superadmin');

        $uni = University::create([
            'name'    => 'Delete U',
            'code'    => 'DU',
            'address' => '101 Delete Ave',
        ]);

        $dept = Department::create([
            'name'          => 'To Be Deleted',
            'code'          => 'DEL',
            'university_id' => $uni->id,
        ]);

        $response = $this->deleteJson("/api/admin/departments/{$dept->id}");

        $response->assertNoContent(); // HTTP 204

        $this->assertDatabaseMissing('departments', [
            'id' => $dept->id,
        ]);
    }

    public function testListByUniversityEndpoint()
    {
        $this->actingAsRole('superadmin');

        $uniA = University::create([
            'name'    => 'Uni A',
            'code'    => 'UA',
            'address' => '1 A St',
        ]);
        $uniB = University::create([
            'name'    => 'Uni B',
            'code'    => 'UB',
            'address' => '2 B St',
        ]);

        // two for A
        Department::create(['name'=>'Dept A1','code'=>'A1','university_id'=>$uniA->id]);
        Department::create(['name'=>'Dept A2','code'=>'A2','university_id'=>$uniA->id]);

        // three for B
        Department::create(['name'=>'Dept B1','code'=>'B1','university_id'=>$uniB->id]);
        Department::create(['name'=>'Dept B2','code'=>'B2','university_id'=>$uniB->id]);
        Department::create(['name'=>'Dept B3','code'=>'B3','university_id'=>$uniB->id]);

        $responseA = $this->getJson("/api/admin/departments/university/{$uniA->id}");
        $responseB = $this->getJson("/api/admin/departments/university/{$uniB->id}");

        $responseA->assertOk()
            ->assertJsonCount(2);

        $responseB->assertOk()
            ->assertJsonCount(3);
    }
}
