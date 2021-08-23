<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DevelopersTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     *
     * @test
     */
    public function retornar_todos_os_desenvolvedores()
    {
        \App\Models\Developer::factory()->count(5)->create();

        $response = $this->get('/developers');

        $response->response->assertOk();

        $content = json_decode($response->response->getContent());

        $this->assertCount(5, $content->data);
    }

    /**
     *
     * @test
     */
    public function deve_ser_possivel_filtrar_desenvolvedores_por_nome_idade_sexo_datanascimento_e_hobby()
    {
        $randomDeveloper = \App\Models\Developer::factory()->create();
        $developer = \App\Models\Developer::factory([
            'nome' => 'Andre Luis Monteiro',
            'idade' => 21,
            'sexo' => 'M',
            'datanascimento' => '1998-01-19',
            'hobby' => 'Jogar'
        ])->create();

        $response = $this->get('/developers?nome=andre');

        $response->response->assertOk();

        $content = json_decode($response->response->getContent());

        $this->assertCount(1, $content->data);
    }

    /**
     *
     * @test
     */
    public function ao_filtrar_desenvolvedores_sem_nenhum_resultado_deve_retornar_404()
    {
        $randomDeveloper = \App\Models\Developer::factory()->create();
        $developer = \App\Models\Developer::factory([
            'nome' => 'Andre Luis Monteiro',
            'idade' => 21,
            'sexo' => 'M',
            'datanascimento' => '1998-01-19',
            'hobby' => 'Jogar'
        ])->create();

        $response = $this->get('/developers?nome=pedro');

        $response->response->assertNotFound();

        $content = json_decode($response->response->getContent());

        $this->assertCount(0, $content->data);
    }

    /**
     *
     * @test
     */
    public function buscar_um_desenvolvedor_inexistente_deve_retornar_404()
    {
        $response = $this->get('/developers/1');

        $response->response->assertNotFound();
    }


    /**
     *
     * @test
     */
    public function buscar_um_desenvolvedor()
    {
        $developer = \App\Models\Developer::factory()->create();

        $response = $this->get('/developers/1');

        $response->response->assertJson([
            'data' => [
                'id' => $developer->id,
                'nome' => $developer->nome,
                'idade' => $developer->idade,
                'datanascimento' => $developer->datanascimento,
                'hobby' => $developer->hobby,
            ]
        ]);
    }

    /**
     *
     * @test
     */
    public function cadastrar_um_desenvolvedor() {
        $developer = \App\Models\Developer::factory([
            'datanascimento' => '2000-01-01'
        ])->raw();

        $response = $this->post('/developers', $developer);

        $response->response->assertCreated();

        $this->seeInDatabase('developers', [
            'nome' => $developer['nome'],
            'sexo' => $developer['sexo'],
            'idade' => 21,
            'datanascimento' => $developer['datanascimento'],
            'hobby' => $developer['hobby']
        ]);
    }

    /**
     *
     * @test
     */
    public function ao_cadastrar_um_desenvolvedor_deve_ser_feita_uma_validacao() {
        $response = $this->post('/developers', []);

        $response->response->assertStatus(400);

        $response->response->assertJson([
            'errors' => [
                'nome' => ['O campo nome é obrigatório'],
                'sexo' => ['O campo sexo é obrigatório'],
                'datanascimento' => ['O campo data de nascimento é obrigatório'],
                'hobby' => ['O campo hobby é obrigatório'],
            ]
        ]);
    }

    /**
     *
     * @test
     */
    public function tentar_remover_um_desenvolvedor_que_nao_existe_deve_retornor_um_status_404() {
        $developer = \App\Models\Developer::factory()->create();
        $data = \App\Models\Developer::factory([
            'datanascimento' => '2000-01-01'
        ])->raw();

        $response = $this->put('/developers/2', $data);

        $response->response->assertNotFound();
    }

    /**
     *
     * @test
     */
    public function deve_ser_possivel_atualizar_um_desenvolvedor() {
        $developer = \App\Models\Developer::factory()->create();

        $response = $this->put('/developers/1', []);

        $response->response->assertStatus(400);

        $response->response->assertJson([
            'errors' => [
                'nome' => ['O campo nome é obrigatório'],
                'sexo' => ['O campo sexo é obrigatório'],
                'datanascimento' => ['O campo data de nascimento é obrigatório'],
                'hobby' => ['O campo hobby é obrigatório'],
            ]
        ]);
    }

    /**
     *
     * @test
     */
    public function ao_atualizar_um_desenvolvedor_deve_ser_feita_uma_validacao() {
        $developer = \App\Models\Developer::factory()->create();
        $data = \App\Models\Developer::factory([
            'datanascimento' => '2000-01-01'
        ])->raw();

        $response = $this->put('/developers/1', $data);

        $response->response->assertOk();

        $this->seeInDatabase('developers', [
            'nome' => $data['nome'],
            'sexo' => $data['sexo'],
            'idade' => 21,
            'datanascimento' => $data['datanascimento'],
            'hobby' => $data['hobby']
        ]);

        $this->missingFromDatabase('developers', [
            'nome' => $developer['nome'],
            'sexo' => $developer['sexo'],
            'idade' => $developer['idade'],
            'datanascimento' => $developer['datanascimento'],
            'hobby' => $developer['hobby']
        ]);
    }

    /**
     *
     * @test
     */
    public function deve_ser_possivel_remover_um_desenvolvedor() {
        $developer = \App\Models\Developer::factory()->create();

        $response = $this->delete('/developers/1');

        $response->response->assertOk();

        $this->missingFromDatabase('developers', [
            'nome' => $developer['nome'],
            'sexo' => $developer['sexo'],
            'idade' => $developer['idade'],
            'datanascimento' => $developer['datanascimento'],
            'hobby' => $developer['hobby']
        ]);
    }

    /**
     *
     * @test
     */
    public function ao_tentar_remover_um_desenvolvedor_inexistente_deve_retornar_status_404() {
        $response = $this->delete('/developers/1');

        $response->response->assertNotFound();
    }
}
