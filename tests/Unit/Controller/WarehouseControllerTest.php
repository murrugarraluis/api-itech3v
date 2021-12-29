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
    public function test_index()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }
    public function test_index_deleted()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/deleted")
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_show()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/$warehouse->id")
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
        $warehouse = Warehouse::factory()->create([
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ]);
        $warehouse->delete();
        $this->actingAs($user)->withSession(['banned' => false])->getJson("api/$this->uri/deleted/$warehouse->name")
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
            'name' => 'Almacen 01',
            'description' => 'Breve Descripcion',
        ];
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->postJson("api/$this->uri", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$warehouse->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/100", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$warehouse->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/$warehouse->id", $json)
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/$warehouse->id", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->deleteJson("api/$this->uri/100", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/deleted/$warehouse->name/restore", [])
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
        $user = User::factory()->create(['name'=>'Luis','email'=>'Luis@gmail.com','password'=>bcrypt('123456')]);
        $this->actingAs($user)->withSession(['banned' => false])->putJson("api/$this->uri/deleted/100/restore", [])
            ->assertStatus(400)
            ->assertJson(['errors' => []]);
    }
}
