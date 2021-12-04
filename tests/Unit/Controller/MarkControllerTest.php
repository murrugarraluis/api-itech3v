<?php

namespace Tests\Unit\Controller;

use App\Models\Mark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'marks';

    public function test_index()
    {
//        $this->withExceptionHandling();
        Mark::factory()->create([
            'name'=>'Categoria 1',
        ]);
        $this->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data'=>[]]);
    }
    public function test_index_deleted()
    {
        $this->withExceptionHandling();
        $Mark =Mark::factory()->create([
            'name'=>'Categoria 1',
        ]);
        $Mark->delete();
        $this->getJson("api/$this->uri/deleted")
            ->assertStatus(200)
            ->assertJson(['data'=>[]]);
    }

    public function test_show(){
        $this->withExceptionHandling();
        $Mark = Mark::factory()->create([
            'name'=>'Categoria 1',
        ]);
        $this->getJson("api/$this->uri/$Mark->id")
            ->assertStatus(200)
            ->assertJson(['data'=>[]]);
    }
    public function test_show_validate_resource_not_exist(){
        $this->withExceptionHandling();
        $this->getJson("api/$this->uri/100")
            ->assertStatus(400)
            ->assertJson(['errors'=>[]]);
    }

    public function test_show_deleted(){
        $this->withExceptionHandling();
        $Mark = Mark::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $Mark->delete();
        $this->getJson("api/$this->uri/deleted/$Mark->name")
            ->assertStatus(200)
            ->assertJson(['data'=>[]]);
    }
    public function test_show_deleted_validate_resource_not_exist(){
        $this->withExceptionHandling();
        $this->getJson("api/$this->uri/deleted/100")
            ->assertStatus(400)
            ->assertJson(['errors'=>[]]);
    }

    public function test_store(){
        $this->withoutExceptionHandling();
        $json = [
            'name'=>'Categoria 01',
        ];
        $this->postJson("api/$this->uri", $json)
            ->assertStatus(201)
            ->assertJson(['message'=>'Marca Registrada']);
        $this->assertDatabaseHas("$this->uri",$json);
    }
    public function test_store_validate_data_unique(){
        Mark::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $json = [
            'name'=>'Categoria 01',
        ];
        $this->postJson("api/$this->uri", $json)
            ->assertStatus(422)
            ->assertJson(['message'=>'Los datos proporcionado no son válidos']);
    }
    public function test_store_validate_data_empty(){
//        $this->withoutExceptionHandling();
        Mark::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $json = [];
        $this->postJson("api/$this->uri", $json)
            ->assertStatus(422)
            ->assertJson(['message'=>'Los datos proporcionado no son válidos']);
    }

    public function test_update(){
        $Mark = Mark::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $json = [
            'name'=>'Almacen A',
        ];
        $this->putJson("api/$this->uri/$Mark->id", $json)
            ->assertStatus(200)
            ->assertJson(['message'=>'Marca Actualizada']);
        $this->assertDatabaseHas("$this->uri",$json);

    }
    public function test_update_validate_resource_not_exist(){
        $json = [
            'name'=>'Categoria A',
        ];
        $this->putJson("api/$this->uri/100", $json)
            ->assertStatus(400)
            ->assertJson(['errors'=>[]]);


    }
    public function test_update_validate_data_unique_self(){
        $Mark = Mark::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $json = [
            'name'=>'Categoria 01',
        ];
        $this->putJson("api/$this->uri/$Mark->id", $json)
            ->assertStatus(200)
            ->assertJson(['message'=>'Marca Actualizada']);

    }
    public function test_update_validate_data_unique(){
        $Mark = Mark::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $Mark = Mark::factory()->create([
            'name'=>'Almacen 02',
        ]);
        $json = [
            'name'=>'Almacen 01',
        ];
        $this->putJson("api/$this->uri/$Mark->id", $json)
            ->assertStatus(422)
            ->assertJson(['message'=>'Los datos proporcionado no son válidos']);


    }
    public function test_update_validate_data_empty(){
        $Mark = Mark::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $json = [];
        $this->putJson("api/$this->uri/$Mark->id", $json)
            ->assertStatus(422)
            ->assertJson(['message'=>'Los datos proporcionado no son válidos']);
    }

    public function test_destroy(){
        $this->withExceptionHandling();
        $Mark = Mark::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $this->deleteJson("api/$this->uri/$Mark->id")
            ->assertStatus(200)
            ->assertJson(['message'=>'Marca Eliminada']);
        $this->assertDatabaseMissing("$this->uri",[
            'id'=>$Mark->id,
            'deleted_at'=>null
        ]);

    }
    public function test_destroy_validate_resoruce_not_exist(){
        $this->withExceptionHandling();
        $this->deleteJson("api/$this->uri/100")
            ->assertStatus(400)
            ->assertJson(['errors'=>[]]);
    }

    public function test_restore(){
        $this->withExceptionHandling();
        $Mark = Mark::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $Mark->delete();
        $this->putJson("api/$this->uri/deleted/$Mark->name/restore")
            ->assertStatus(200)
            ->assertJson(['message'=>'Marca Restaurada']);
        $this->assertDatabaseHas("$this->uri",[
            'id'=>$Mark->id,
            'deleted_at'=>null
        ]);

    }
    public function test_restore_validate_resoruce_not_exist(){
        $this->withExceptionHandling();
        $this->putJson("api/$this->uri/deleted/100/restore")
            ->assertStatus(400)
            ->assertJson(['errors'=>[]]);
    }
}
