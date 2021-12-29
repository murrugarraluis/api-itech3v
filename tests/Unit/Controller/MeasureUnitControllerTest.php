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

    public function test_index()
    {
        //        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        MeasureUnit::factory()->create([
            'name' => 'Categoria 1',
        ]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_index_deleted()
    {
        
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 1',
        ]);
        $MeasureUnit->delete();
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/deleted")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_show()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 1',
        ]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$MeasureUnit->id")
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

    public function test_show_deleted()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Categoria 01',
        ]);
        $MeasureUnit->delete();
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/deleted/$MeasureUnit->name")
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

    public function test_store()
    {
        $this->withoutExceptionHandling();
        $json = [
            'name' => 'Categoria 01',
        ];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$MeasureUnit->id", $json)
            ->assertStatus(200)
            ->assertJson(['message' => 'Unidad de Medida Actualizada']);
        $this->assertDatabaseHas("$this->table", $json);
    }
    public function test_update_validate_resource_not_exist()
    {
        $json = [
            'name' => 'Categoria A',
        ];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/100", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$MeasureUnit->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$MeasureUnit->id", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }
    public function test_update_validate_data_empty()
    {
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Almacen 01',
        ]);
        $json = [];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$MeasureUnit->id", $json)
            ->assertStatus(422)
            ->assertJson(['message' => 'Los datos proporcionado no son válidos']);
    }

    public function test_destroy()
    {
        $this->withExceptionHandling();
        $MeasureUnit = MeasureUnit::factory()->create([
            'name' => 'Almacen 01',
        ]);
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$MeasureUnit->id", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/100", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/deleted/$MeasureUnit->name/restore", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/deleted/100/restore", [])
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }
}
