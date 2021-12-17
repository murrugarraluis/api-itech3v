<?php

namespace Tests\Unit\Controller;

use App\Models\MeasureUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeasureUnitControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'measure-units';
    private $table = 'measure_units';

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
        //        $this->withExceptionHandling();
        MeasureUnit::factory()->create([
            'name' => 'Categoria 1',
        ]);
        $this->getJson("api/$this->uri", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_index_deleted()
    {
        $this->withExceptionHandling();
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 1',
        ]);
        $MeasureUnit->delete();
        $this->getJson("api/$this->uri/deleted", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_show()
    {
        $this->withExceptionHandling();
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 1',
        ]);
        $this->getJson("api/$this->uri/$MeasureUnit->id", $this->login())
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
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 01',
        ]);
        $MeasureUnit->delete();
        $this->getJson("api/$this->uri/deleted/$MeasureUnit->name", $this->login())
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
            'name' => 'Categoria 01',
        ];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(201)
            ->assertJson(['message' => 'Unidad de Medida Registrada']);
        $this->assertDatabaseHas("$this->table", $json);
    }
    public function test_store_validate_data_unique()
    {
        MeasureUnit::factory()->create([
            'name' => 'Categoria 01',
        ]);
        $json = [
            'name' => 'Categoria 01',
        ];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_store_validate_data_empty()
    {
        //        $this->withoutExceptionHandling();
        MeasureUnit::factory()->create([
            'name' => 'Categoria 01',
        ]);
        $json = [];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    public function test_update()
    {
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 01',
        ]);
        $json = [
            'name' => 'Almacen A',
        ];
        $this->putJson("api/$this->uri/$MeasureUnit->id", $json, $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Unidad de Medida Actualizada']);
        $this->assertDatabaseHas("$this->table", $json);
    }
    public function test_update_validate_resource_not_exist()
    {
        $json = [
            'name' => 'Categoria A',
        ];
        $this->putJson("api/$this->uri/100", $json, $this->login())
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }
    public function test_update_validate_data_unique_self()
    {
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 01',
        ]);
        $json = [
            'name' => 'Categoria 01',
        ];
        $this->putJson("api/$this->uri/$MeasureUnit->id", $json, $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Unidad de Medida Actualizada']);
    }
    public function test_update_validate_data_unique()
    {
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Almacen 01',
        ]);
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Almacen 02',
        ]);
        $json = [
            'name' => 'Almacen 01',
        ];
        $this->putJson("api/$this->uri/$MeasureUnit->id", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_update_validate_data_empty()
    {
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Almacen 01',
        ]);
        $json = [];
        $this->putJson("api/$this->uri/$MeasureUnit->id", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    public function test_destroy()
    {
        $this->withExceptionHandling();
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Almacen 01',
        ]);
        $this->deleteJson("api/$this->uri/$MeasureUnit->id", [], $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Unidad de Medida Eliminada']);
        $this->assertDatabaseMissing("$this->table", [
            'id' => $MeasureUnit->id,
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
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Almacen 01',
        ]);
        $MeasureUnit->delete();
        $this->putJson("api/$this->uri/deleted/$MeasureUnit->name/restore", [], $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Unidad de Medida Restaurada']);
        $this->assertDatabaseHas("$this->table", [
            'id' => $MeasureUnit->id,
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
