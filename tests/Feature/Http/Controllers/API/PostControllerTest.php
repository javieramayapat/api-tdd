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

}

