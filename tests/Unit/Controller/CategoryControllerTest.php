<?php

namespace Tests\Unit\Controller;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = 'categories';

    public function test_index()
    {
//        $this->withExceptionHandling();
        Category::factory()->create([
            'name'=>'Categoria 1',
        ]);
        $this->getJson("api/$this->uri")
            ->assertStatus(200)
            ->assertJson(['data'=>[]]);
    }
    public function test_index_deleted()
    {
        $this->withExceptionHandling();
        $category =Category::factory()->create([
            'name'=>'Categoria 1',
        ]);
        $category->delete();
        $this->getJson("api/$this->uri/deleted")
            ->assertStatus(200)
            ->assertJson(['data'=>[]]);
    }

    public function test_show(){
        $this->withExceptionHandling();
        $category = category::factory()->create([
            'name'=>'Categoria 1',
        ]);
        $this->getJson("api/$this->uri/$category->id")
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
        $category = category::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $category->delete();
        $this->getJson("api/$this->uri/deleted/$category->name")
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
            ->assertJson(['message'=>'Categoria Registrada']);
        $this->assertDatabaseHas("$this->uri",$json);
    }
    public function test_store_validate_data_unique(){
        category::factory()->create([
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
        category::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $json = [];
        $this->postJson("api/$this->uri", $json)
            ->assertStatus(422)
            ->assertJson(['message'=>'Los datos proporcionado no son válidos']);
    }

    public function test_update(){
        $category = category::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $json = [
            'name'=>'Almacen A',
        ];
        $this->putJson("api/$this->uri/$category->id", $json)
            ->assertStatus(200)
            ->assertJson(['message'=>'Categoria Actualizada']);
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
        $category = category::factory()->create([
            'name'=>'Categoria 01',
        ]);
        $json = [
            'name'=>'Categoria 01',
        ];
        $this->putJson("api/$this->uri/$category->id", $json)
            ->assertStatus(200)
            ->assertJson(['message'=>'Categoria Actualizada']);

    }
    public function test_update_validate_data_unique(){
        $category = category::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $category = category::factory()->create([
            'name'=>'Almacen 02',
        ]);
        $json = [
            'name'=>'Almacen 01',
        ];
        $this->putJson("api/$this->uri/$category->id", $json)
            ->assertStatus(422)
            ->assertJson(['message'=>'Los datos proporcionado no son válidos']);


    }
    public function test_update_validate_data_empty(){
        $category = category::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $json = [];
        $this->putJson("api/$this->uri/$category->id", $json)
            ->assertStatus(422)
            ->assertJson(['message'=>'Los datos proporcionado no son válidos']);
    }

    public function test_destroy(){
        $this->withExceptionHandling();
        $category = category::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $this->deleteJson("api/$this->uri/$category->id")
            ->assertStatus(200)
            ->assertJson(['message'=>'Categoria Eliminada']);
        $this->assertDatabaseMissing("$this->uri",[
            'id'=>$category->id,
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
        $category = category::factory()->create([
            'name'=>'Almacen 01',
        ]);
        $category->delete();
        $this->putJson("api/$this->uri/deleted/$category->name/restore")
            ->assertStatus(200)
            ->assertJson(['message'=>'Categoria Restaurada']);
        $this->assertDatabaseHas("$this->uri",[
            'id'=>$category->id,
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
