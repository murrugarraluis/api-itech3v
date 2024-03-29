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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri")
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/deleted")
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$Material->id")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_show_validate_resource_not_exist()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/100")
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/deleted/$Material->name")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_show_deleted_validate_resource_not_exist()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/deleted/100")
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    public function test_store_validate_data_empty()
    {
        //        $this->withoutExceptionHandling();
        $json = [];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$Material->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/100", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$Material->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$MaterialB->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$Material->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$Material->id", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/100", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/deleted/$Material->name/restore", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/deleted/100/restore", [])
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }
}
