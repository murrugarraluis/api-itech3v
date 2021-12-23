<?php

namespace Tests\Unit\Controller;

use App\Models\Category;
use App\Models\Mark;
use App\Models\Material;
use App\Models\MeasureUnit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'materials';
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
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        $this->getJson("api/$this->uri", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_index_deleted()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $Material->delete();
        $this->getJson("api/$this->uri/deleted", $this->login())
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    //
    public function test_show()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        $this->getJson("api/$this->uri/$Material->id", $this->login())
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

    //
    public function test_show_deleted()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $Material->delete();
        $this->getJson("api/$this->uri/deleted/$Material->name", $this->login())
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

    //
    public function test_store()
    {
        $this->withoutExceptionHandling();
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 02',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Camara QHD ZX-77HF',
            'category' => 1,
            'mark' => 1,
            'minimum_stock' => 10,
            'measure_unit' => 1,
            'warehouses' => [['id' => 1, 'quantity' => 5], ['id' => 2, 'quantity' => 3]]
        ];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(201)
            ->assertJson(['message' => 'Material Registrado']);
    }

    public function test_store_validate_data_unique()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $json = [
            'name' => 'Camara QHD ZX-77HF',
            'category' => 1,
            'mark' => 1,
            'measure_unit' => 1,
        ];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    public function test_store_validate_data_empty()
    {
        //        $this->withoutExceptionHandling();
        $json = [];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_store_data_empty_warehouses()
    {
        $this->withoutExceptionHandling();
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 02',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Camara QHD ZX-77HF',
            'category' => 1,
            'mark' => 1,
            'minimum_stock' => 10,
            'measure_unit' => 1,
            'warehouses' => [['id' => 1], ['id' => 2]]
        ];
        $this->postJson("api/$this->uri", $json, $this->login())
            ->assertStatus(201)
            ->assertJson(['message' => 'Material Registrado']);
    }
    //
    public function test_update()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Category = Category::factory()->create(['name' => 'Laptops']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 02',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Camara QHD ZX-77HF',
            'category' => 1,
            'mark' => 1,
            'measure_unit' => 1,
            'warehouses' => [['id' => 1, 'quantity' => 5], ['id' => 2, 'quantity' => 3]]
        ];
        $this->putJson("api/$this->uri/$Material->id", $json, $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Material Actualizado']);
    }

    public function test_update_validate_resource_not_exist()
    {
        $json = [
            'name' => 'Camara QHD ZX-77HF',
            'category' => 1,
            'mark' => 1,
            'measure_unit' => 1,
        ];
        $this->putJson("api/$this->uri/100", $json, $this->login())
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }

    public function test_update_validate_data_unique_self()
    {
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Category = Category::factory()->create(['name' => 'Laptops']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 02',
            'description' => 'Breve Descripcion',
        ]);
        $json = [
            'name' => 'Camara QHD ZX-77HF',
            'category' => 1,
            'mark' => 1,
            'measure_unit' => 1,
            'warehouses' => [['id' => 1, 'quantity' => 5], ['id' => 2, 'quantity' => 3]]
        ];
        $this->putJson("api/$this->uri/$Material->id", $json, $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Material Actualizado']);
    }

    public function test_update_validate_data_unique()
    {
        $Category = Category::factory()->create(['name' => 'Laptops']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $MaterialA = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $MaterialA->category()->associate($Category)->save();
        $MaterialA->mark()->associate($Mark)->save();
        $MaterialA->measure_unit()->associate($MeasureUnit)->save();

        $MaterialB = Material::factory()->create(['name' => 'Camara QQHD ZX-77HF', 'minimum_stock' => 5]);
        $MaterialB->category()->associate($Category)->save();
        $MaterialB->mark()->associate($Mark)->save();
        $MaterialB->measure_unit()->associate($MeasureUnit)->save();

        $json = [
            'name' => 'Camara QHD ZX-77HF',
            'category' => 1,
            'mark' => 1,
            'measure_unit' => 1,
        ];
        $this->putJson("api/$this->uri/$MaterialB->id", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    public function test_update_validate_data_empty()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Category = Category::factory()->create(['name' => 'Laptops']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        $json = [];
        $this->putJson("api/$this->uri/$Material->id", $json, $this->login())
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    //
    public function test_destroy()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $this->deleteJson("api/$this->uri/$Material->id", [], $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Material Eliminado']);
        $this->assertDatabaseMissing("$this->uri", [
            'id' => $Material->id,
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

    //
    public function test_restore()
    {
        $this->withExceptionHandling();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $Material->delete();
        $this->putJson("api/$this->uri/deleted/$Material->name/restore", [], $this->login())
            ->assertStatus(200)
            ->assertJson(['message' => 'Material Restaurado']);
        $this->assertDatabaseHas("$this->uri", [
            'id' => $Material->id,
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
