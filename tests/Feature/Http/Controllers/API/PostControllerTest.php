<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{

    /* 
        Defina ganchos para migrar la base de datos antes y después de cada prueba.
    */
    use RefreshDatabase;

    public function test_store()
    {
        // Llamada al manejador de errores para mostrar de manera más concisa la falla.
        $this->withoutExceptionHandling();
        /* 
            Simulación de una aplicación esta llegando mediante la ruta
            /api/posts y esta intentando guardar los datos mediante el
            método post.
        */
        $response = $this->json('POST', '/api/posts', [
            'title' => 'El post de prueba'
        ]);

        /* 
            Comprobar que se esta guardando los datos
            ->assertJsonStructure() : confirmación de estructura
            ->assertJson() : Confirmación de dato existente en Json
            ->assertStatus(201) : Confirmación de status

        */
        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])
            ->assertJson(['title' => 'El post de prueba'])
            ->assertStatus(201);

        $this->assertDatabaseHas('posts', ['title' => 'El post de prueba']);
    }

    /* 
        Casos del test: Supongamos que un usuarios desde su celular
        intente guardar el titulo ¿Que medida debemo tomar como desarrolladorers?
    */

    public function test_validate_title()
    {
        /* 
         */
        // intento de guardar un post sin titulo
        $response = $this->json('POST', 'api/posts', [
            'title' => ''
        ]);

        /*  validacion que no se pueda guardar una solicitud sin titulo
            validando un error 422 y que tambien se tenga un json de errores
            indicando que el campo titulo debe venir vacio
        */

        $response->assertStatus(422)
                ->assertJsonValidationErrors('title');
    }

    /* 
        $response: Equivale a la acción que el usuario esta haciendo a una uri de nuestra api,
                   por ellos mediante las afirmaciones hacemos Debugging Responses 
                   para que se que se cumpla la logica de negocio esperada.

        1.Debo crear un post mediante factory
        2.valido la ruta de consulta de ese post obteniendo el id directamente del post de prueba
        3.verifico que tenga una estructura json valida
        4. Varifico que tenga un json con el titulo de respuesta
        5.verifico que me de un status 200 ok

        ->assertJson(): Afirma que en la respuesta contenga un elemento de la estructura json dada.

    */

    
    public function test_show()
    {
        $post = factory(Post::class)->create();

        $response = $this->json('GET',"api/posts/$post->id");

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
                ->assertJson(['title' => $post->title])
                ->assertStatus(200);
    }

    /* 
        Creación de prueba para un error 404, caso en el que el post no exista
    */

    public function test_404_show()
    {
        // al no usar variables podemos usar comillas sencillas
        $response = $this->json('GET','api/posts/1000');

        $response->assertStatus(404);
    }

    /* 
        1.Crear un post con factory.
        2.Configuro la solicitud para modificar el recurso medinate put y su ruta respectiva.
        3.Verificar estrucutra de json, valor-key y  status ok.
        4.verificar el valor y la key en la base de datos.
    */
    public function test_update(){

        // Llamada al manejador de errores para mostrar de manera más concisa la falla.
        // $this->withoutExceptionHandling();
        
        $post = factory(Post::class)->create();

        $response = $this->json('PUT',"api/posts/$post->id",[
            'title' => 'nuevo'
        ]);

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
                 ->assertJson(['title' => 'nuevo'])
                 ->assertStatus(200);

        $this->assertDatabaseHas('posts', ['title' => 'nuevo']);
    }

}

