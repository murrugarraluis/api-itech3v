<?php

namespace Tests\Unit\Controller;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'suppliers';
    public function test_index()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_show()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$supplier->id")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_store()
    {
        $this->withExceptionHandling();
        $json = [
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(201)
            ->assertJson(['message' => 'Proveedor Registrado']);
        $this->assertDatabaseHas("$this->uri", $json);
    }
    public function test_store_validate_data_unique()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $json = [
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_update()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $json = [
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra Astolingon',
        ];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$supplier->id", $json)
            ->assertStatus(200)
            ->assertJson(['message' => 'Proveedor Actualizado']);
        $this->assertDatabaseHas("$this->uri", $json);
    }
    public function test_update_validate_data_unique()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579608',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $json = [
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra Astolingon',
        ];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$supplier->id", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_destroy()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$supplier->id", [])
            ->assertStatus(200)
            ->assertJson(['message' => 'Proveedor Eliminado']);
        $this->assertDatabaseMissing("$this->uri", [
            'id' => $supplier->id,
            'deleted_at' => null
        ]);
    }

}
