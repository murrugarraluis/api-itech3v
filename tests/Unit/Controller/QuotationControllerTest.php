<?php

namespace Tests\Unit\Controller;

use App\Models\Category;
use App\Models\Mark;
use App\Models\Material;
use App\Models\MeasureUnit;
use App\Models\Quotation;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'quotations';
    private $table = 'quotations';
    public function test_index()
    {
        $supplier = Supplier::factory()->create([
            'type_document'=>'DNI',
            'number_document'=>'75579609',
            'name'=>'Luis',
            'lastname'=>'Murrugarra',
        ]);
        $this->withExceptionHandling();
        $quotation = Quotation::factory()->create([
            'date_agreed' => '2022-01-05',
            'way_to_pay' => 'Contado',
            'type_quotation' => 'Por Requerimiento',
            'document_number' => '0000011',
        ]);
        $quotation->supplier()->associate($supplier->id)->save();

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        //        Agregar Producto al detalle de Requerimiento
        $quotation->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_show()
    {
        $supplier = Supplier::factory()->create([
            'type_document'=>'DNI',
            'number_document'=>'75579609',
            'name'=>'Luis',
            'lastname'=>'Murrugarra',
        ]);
        $this->withExceptionHandling();
        $quotation = Quotation::factory()->create([
            'date_agreed' => '2022-01-05',
            'way_to_pay' => 'Contado',
            'type_quotation' => 'Por Requerimiento',
            'document_number' => '0000011',
        ]);
        $quotation->supplier()->associate($supplier->id)->save();

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        //        Agregar Producto al detalle de Requerimiento
        $quotation->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$quotation->id")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_store()
    {
        $supplier = Supplier::factory()->create([
            'type_document'=>'DNI',
            'number_document'=>'75579609',
            'name'=>'Luis',
            'lastname'=>'Murrugarra',
        ]);
        $this->withExceptionHandling();
        // $quotation = Quotation::factory()->create([
        //     'date_agreed' => '2022-01-05',
        //     'way_to_pay' => 'Contado',
        //     'type_quotation' => 'Por Requerimiento',
        //     'document_number' => '0000011',
        // ]);
        // $quotation->supplier()->associate($supplier->id)->save();
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
            'supplier' => $supplier->id,
            'date_agreed' => '2022-01-05',
            'way_to_pay' => 'Contado',
            'type_quotation' => 'Por Requerimiento',
            'document_number' => '0111',
            'materials' => [['id' => 1, 'quantity' => 5,'price' =>2.5], ['id' => 2, 'quantity' => 3,'price' =>2.5]]
        ];
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(201)
            ->assertJson(['message' => 'Cotizacion Registrada']);
    }
    public function test_destroy()
    {
        $supplier = Supplier::factory()->create([
            'type_document'=>'DNI',
            'number_document'=>'75579609',
            'name'=>'Luis',
            'lastname'=>'Murrugarra',
        ]);
        $this->withExceptionHandling();
        $quotation = Quotation::factory()->create([
            'date_agreed' => '2022-01-05',
            'way_to_pay' => 'Contado',
            'type_quotation' => 'Por Requerimiento',
            'document_number' => '0000011',
        ]);
        $quotation->supplier()->associate($supplier->id)->save();

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        //        Agregar Producto al detalle de Requerimiento
        $quotation->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$quotation->id", [])
            ->assertStatus(200)
            ->assertJson(['message' => 'Cotizacion Eliminada']);
        $this->assertDatabaseMissing("$this->table", [
            'id' => $quotation->id,
            'deleted_at' => null
        ]);
    }
}
