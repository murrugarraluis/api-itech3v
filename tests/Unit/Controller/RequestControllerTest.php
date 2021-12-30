<?php

namespace Tests\Unit\Controller;

use App\Models\Category;
use App\Models\Mark;
use App\Models\Material;
use App\Models\MeasureUnit;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'requests';
    public function test_index()
    {
        $this->withExceptionHandling();
        $Request = Request::factory()->create([
            'date_required' => '2022-01-05',
            'type_request' => 'Para Operaciones',
            'importance' => 'Media',
            'comment' => '',
        ]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        //        Agregar Producto al detalle de Requerimiento
        $Request->materials()->attach([
            1 => ['quantity' => 5],
        ]);

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_index_filter_with_user()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $Request = Request::factory()->create([
            'date_required' => '2022-01-05',
            'type_request' => 'Para Operaciones',
            'importance' => 'Media',
            'comment' => '',
        ]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        //        Agregar Producto al detalle de Requerimiento
        $Request->materials()->attach([
            1 => ['quantity' => 5],
        ]);

        $user->requests()->save($Request);
        $user->refresh();
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/users/$user->id/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }


    public function test_show()
    {
        //        $this->withExceptionHandling();
        $Request = Request::factory()->create([
            'date_required' => '2022-01-05',
            'type_request' => 'Para Operaciones',
            'importance' => 'Media',
            'comment' => '',
        ]);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        //        Agregar Producto al detalle de Requerimiento
        $Request->materials()->attach([
            1 => ['quantity' => 5],
        ]);
        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$Request->id")
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
    //
    public function test_store()
    {
        $this->withoutExceptionHandling();
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-7e7HF', 'minimum_stock' => 5]);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();

        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $json = [
            'user_id'=> $user->id,
            'date_required' => '2022-01-05',
            'type_request' => 'Para Operaciones',
            'importance' => 'Media',
            'comment' => '',
            'materials' => [['id' => 1, 'quantity' => 5], ['id' => 2, 'quantity' => 3]]
        ];

        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(201)
            ->assertJson(['message' => 'Requerimiento Registrado']);
        //        $this->assertDatabaseHas("$this->uri",$json);
    }

    public function test_store_validate_data_empty()
    {
        //        $this->withoutExceptionHandling();
        $json = [];
        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }


    public function test_destroy()
    {
        //        $this->withExceptionHandling();
        $Request = Request::factory()->create([
            'date_required' => '2022-01-05',
            'type_request' => 'Para Operaciones',
            'importance' => 'Media',
            'comment' => '',
        ]);
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF', 'minimum_stock' => 5]);
        $Category = Category::factory()->create(['name' => 'Camaras']);
        $Mark = Mark::factory()->create(['name' => 'Vision']);
        $MeasureUnit = MeasureUnit::factory()->create(['name' => 'Caja']);
        //        Asociar Datos de Material
        $Material->category()->associate($Category)->save();
        $Material->mark()->associate($Mark)->save();
        $Material->measure_unit()->associate($MeasureUnit)->save();
        //        Agregar Producto al detalle de Requerimiento
        $Request->materials()->attach([
            1 => ['quantity' => 5],
        ]);
        $user = User::factory()->create(['name' => 'Luis', 'email' => 'Luis@gmail.com', 'password' => bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$Request->id", [])
            ->assertStatus(200)
            ->assertJson(['message' => 'Requerimiento Eliminado']);
        $this->assertDatabaseMissing("$this->uri", [
            'id' => $Request->id,
            'deleted_at' => null
        ]);
    }

    public function test_destroy_validate_resoruce_not_exist()
    {
        //        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/100", [])
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }
}
