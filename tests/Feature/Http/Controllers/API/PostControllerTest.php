<?php

namespace Tests\Feature\Http\Controllers\Api;

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
        $this->withExceptionHandling();
        /* 
            Simulación de una aplicación esta llegando mediante la ruta
            /api/posts y esta intentando guardar los datos mediante el
            método post.
        */
        $response = $this->json('POST','/api/posts',[
            'title' => 'El post de prueba'
        ]);

        /* 
            Comprobar que se esta guardando los datos
            ->assertJsonStructure() : confirmación de estructura
            ->assertJson() : Confirmación de dato existente en Json
            ->assertStatus(201) : Confirmación de status

        */
        $response->assertJsonStructure(['id','title','created_at','updated_at'])
                 ->assertJson(['title' => 'El post de prueba'])
                 ->assertStatus(201);

        $this->assertDatabaseHas('posts',['title' => 'El post de prueba']);
    }
}
