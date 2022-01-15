<?php

namespace Tests\Unit\Controller;

use App\Models\Category;
use App\Models\EntryNote;
use App\Models\Mark;
use App\Models\Material;
use App\Models\MeasureUnit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryNoteControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'entry-notes';
    private $table = 'entry_notes';
    public function test_index()
    {
        $this->withExceptionHandling();
        $entry_note = EntryNote::factory()->create([
            'date' => '2022-01-05',
            'type_entry' => 'Para Ventas',
            'comment' => '',
            'document_number' => '0000011',
        ]);

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        $almacen = Warehouse::factory()->create(['name' => 'Almacen 01', 'description' => 'Almacen ubicado en la calle Av.Gonzales Caceda']);
        $entry_note->warehouse()->associate($almacen)->save();
        //        Agregar Producto al detalle de Requerimiento
        $entry_note->materials()->attach([
            1 => ['quantity' => 5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_show()
    {
        $this->withExceptionHandling();
        $entry_note = EntryNote::factory()->create([
            'date' => '2022-01-05',
            'type_entry' => 'Para Ventas',
            'comment' => '',
            'document_number' => '0000011',
        ]);

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        $almacen = Warehouse::factory()->create(['name' => 'Almacen 01', 'description' => 'Almacen ubicado en la calle Av.Gonzales Caceda']);
        $entry_note->warehouse()->associate($almacen)->save();
        //        Agregar Producto al detalle de Requerimiento
        $entry_note->materials()->attach([
            1 => ['quantity' => 5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$entry_note->id")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_store()
    {
        $this->withExceptionHandling();
        $warehouse = Warehouse::factory()->create(['name' => 'Almacen 01', 'description' => 'Almacen ubicado en la calle Av.Gonzales Caceda']);
        $warehouse = Warehouse::factory()->create(['name' => 'Almacen 02', 'description' => 'Almacen ubicado en la calle Av.Gonzales Caceda']);

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);

        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' => 5],
            2 => ['quantity' => 3],
        ]);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-7e7HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' => 2],
            2 => ['quantity' => 1],
        ]);
        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $json = [
            'warehouse' => $warehouse->id,
            'date' => '2022-01-05',
            'type_entry' => 'Para Operaciones',
            'comment' => '',
            'document_number' => '0111',
            'materials' => [['id' => 1, 'quantity' => 5], ['id' => 2, 'quantity' => 3]]
        ];
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(201)
            ->assertJson(['message' => 'Nota de Ingreso Registrada']);
    }
    public function test_destroy()
    {
        $this->withExceptionHandling();
        $warehouse = Warehouse::factory()->create(['name' => 'Almacen 01', 'description' => 'Almacen ubicado en la calle Av.Gonzales Caceda']);
        $warehouse = Warehouse::factory()->create(['name' => 'Almacen 02', 'description' => 'QAlmacen ubicado en la calle Av.Gonzales Caceda']);

        $entry_note = EntryNote::factory()->create([
            'date' => '2022-01-05',
            'type_entry' => 'Para Ventas',
            'comment' => '',
            'document_number' => '0000011',
        ]);

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' => 5],
            2 => ['quantity' => 3],
        ]);
        $entry_note->warehouse()->associate($warehouse)->save();
        //        Agregar Producto al detalle de Requerimiento
        $entry_note->materials()->attach([
            1 => ['quantity' => 5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$entry_note->id", [])
            ->assertStatus(200)
            ->assertJson(['message' => 'Nota de Salida Eliminada']);
        $this->assertDatabaseMissing("$this->table", [
            'id' => $entry_note->id,
            'deleted_at' => null
        ]);
    }
}
