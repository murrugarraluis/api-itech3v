<?php

namespace Tests\Unit\Controller;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'warehouses';
    public function login()
    {
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $json = [
            'name' => 'Luis',
            'email' => 'luis17@gmail.com',
            'password' => '123456',
        ];

        $response = $this->postJson("api/login", $json);
        $token = $response->baseResponse->original['token'];
        $header = [
            "Authorization" => "Bearer " . $token
        ];
        return $header;
    }

    public function test_index()
    {
        $this->withExceptionHandling();
        $this->getJson("api/$this->uri", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_index_deleted()
    {
        $this->withExceptionHandling();
        $this->getJson("api/$this->uri/deleted", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_show()
    {
        $this->withExceptionHandling();
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $this->getJson("api/$this->uri/$warehouse->id", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_show_validate_resource_not_exist()
    {
        $this->withExceptionHandling();
        $this->getJson("api/$this->uri/100", $this->login())
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }

    public function test_show_deleted()
    {
        $this->withExceptionHandling();
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse->delete();
        $this->getJson("api/$this->uri/deleted/$warehouse->name", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_show_deleted_validate_resource_not_exist()
    {
        $this->withExceptionHandling();
        $this->getJson("api/$this->uri/deleted/100", $this->login())
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }

    public function test_store()
    {
        $this->withoutExceptionHandling();
        $json = [
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(201)
            ->assertJson(['message' => 'Almacén Registrado']);
        $this->assertDatabaseHas("$this->uri", $json);
    }
    public function test_store_validate_data_unique()
    {
        Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_store_validate_data_empty()
    {
        //        $this->withoutExceptionHandling();
        Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $json = [];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    public function test_update()
    {
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Almacen A',
            'description' => 'Breve Descripcion',
        ];
        $this->putJson("api/$this->uri/$warehouse->id", $json, $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Almacén Actualizado']);
        $this->assertDatabaseHas("$this->uri", $json);
    }
    public function test_update_validate_resource_not_exist()
    {
        $json = [
            'name' => 'Almacen A',
            'description' => 'Breve Descripcion',
        ];
        $this->putJson("api/$this->uri/100", $json, $this->login())
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }
    public function test_update_validate_data_unique()
    {
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 02',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ];
        $this->putJson("api/$this->uri/$warehouse->id", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_update_validate_data_unique_self()
    {
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ];
        $this->putJson("api/$this->uri/$warehouse->id", $json, $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Almacén Actualizado']);
    }

    public function test_destroy()
    {
        $this->withExceptionHandling();
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $this->deleteJson("api/$this->uri/$warehouse->id", [], $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Almacén Eliminado']);
        $this->assertDatabaseMissing("$this->uri", [
            'id' => $warehouse->id,
            'deleted_at' => null
        ]);
    }
    public function test_destroy_validate_resoruce_not_exist()
    {
        $this->withExceptionHandling();
        $this->deleteJson("api/$this->uri/100", [], $this->login())
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }

    public function test_restore()
    {
        $this->withExceptionHandling();
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse->delete();
        $this->putJson("api/$this->uri/deleted/$warehouse->name/restore", [], $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Almacén Restaurado']);
        $this->assertDatabaseHas("$this->uri", [
            'id' => $warehouse->id,
            'deleted_at' => null
        ]);
    }
    public function test_restore_validate_resoruce_not_exist()
    {
        $this->withExceptionHandling();
        $this->putJson("api/$this->uri/deleted/100/restore", [], $this->login())
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }
}
