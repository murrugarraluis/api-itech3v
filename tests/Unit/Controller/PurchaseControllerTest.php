<?php

namespace Tests\Unit\Controller;

use App\Models\Category;
use App\Models\Mark;
use App\Models\Material;
use App\Models\MeasureUnit;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase;
    private $uri = 'purchases';
    private $table = 'purchases';
    public function test_index_report()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $purchase = Purchase::factory()->create([
            'date_required' => '2022-01-01',
            'way_to_pay' => 'Contado',
            'type_document' => 'Boleta',
            'number' => 'B001-0001',
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ]);
        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-05',
            'date_agreed' => '2022-01-05',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'status' => 'Usado'
        ]);
        $purchase->supplier()->associate($supplier->id)->save();
        $purchase->purchase_order()->associate($purchaseOrder->id)->save();

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        //        Agregar Producto al detalle de Requerimiento
        $purchase->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/$this->uri?date_min=2021-10-01 && date_max=2022-01-20")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_index()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document' => 'DNI',
            'number_document' => '75579609',
            'name' => 'Luis',
            'lastname' => 'Murrugarra',
        ]);
        $purchase = Purchase::factory()->create([
            'way_to_pay' => 'Contado',
            'type_document' => 'Boleta',
            'number' => 'B001-0001',
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ]);
        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-05',
            'date_agreed' => '2022-01-05',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'status' => 'Usado'
        ]);
        $purchase->supplier()->associate($supplier->id)->save();
        $purchase->purchase_order()->associate($purchaseOrder->id)->save();

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        //        Agregar Producto al detalle de Requerimiento
        $purchase->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
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
        $purchase = Purchase::factory()->create([
            'way_to_pay' => 'Contado',
            'type_document' => 'Boleta',
            'number' => 'B001-0001',
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ]);
        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-05',
            'date_agreed' => '2022-01-05',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'status' => 'Usado'
        ]);
        $purchase->supplier()->associate($supplier->id)->save();
        $purchase->purchase_order()->associate($purchaseOrder->id)->save();

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        //        Agregar Producto al detalle de Requerimiento
        $purchase->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$purchaseOrder->id")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_store()
    {
        $this->withExceptionHandling();
        $supplier = Supplier::factory()->create([
            'type_document'=>'DNI',
            'number_document'=>'75579609',
            'name'=>'Luis',
            'lastname'=>'Murrugarra',
        ]);
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
        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-05',
            'date_agreed' => '2022-01-05',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'status' => 'Usado'
        ]);
        $json = [
            'supplier' => $supplier->id,
            'way_to_pay' => 'Contado',
            'type_document' => 'Boleta',
            'number' => 'B001-0001',
            'type_purchase' => 'Por Orden de Compra',
            'document_number' => $purchaseOrder->id,
            'materials' => [['id' => 1, 'quantity' => 5, 'price' => 2.5], ['id' => 2, 'quantity' => 3, 'price' => 2.5]]
        ];
        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(201)
            ->assertJson(['message' => 'Compra Registrada']);
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
        $purchase = Purchase::factory()->create([
            'way_to_pay' => 'Contado',
            'type_document' => 'Boleta',
            'number' => 'B001-0001',
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ]);
        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-05',
            'date_agreed' => '2022-01-05',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'status' => 'Usado'
        ]);
        $purchase->supplier()->associate($supplier->id)->save();
        $purchase->purchase_order()->associate($purchaseOrder->id)->save();

        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        //        Agregar Producto al detalle de Requerimiento
        $purchase->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
        ]);


        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$purchaseOrder->id", [])
            ->assertStatus(200)
            ->assertJson(['message' => 'Compra Eliminada']);
        $this->assertDatabaseMissing("$this->table", [
            'id' => $purchaseOrder->id,
            'deleted_at' => null
        ]);
    }
}
