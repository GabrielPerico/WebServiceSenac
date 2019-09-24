<?php

/**
 * Implementação da API rest usando a biblioteca do link abaixo
 * Essa biblioteca possui quatro arquivos distintos:
 * 1 - REST_Controller na pasta libraries, que altera o comportamento padrão das controllers padrões do CI
 * 2 - REST_Controller_Definitions na pasta libraries, que tras algumas definições para o REST_Controller,
 *     trabalha como um arquivo de padrões auxiliando o controller principal
 * 3 - Format na pasta Libraries, que faz o parsing (conversão) dos diferentes tipos de dados (JSON, XML, CSV e etc)
 * 4 - rest.php na pasta config, para as configurações desta biblioteca
 * 
 * @author      Aluno Gabriel Périco
 * @link        https://github.com/chriskacerguis/codeigniter-restserver
 */
use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class V1 extends Rest_Controller    {
    public function __construct(){
        parent::__construct();
    }

    //O nome dos métodos sempre vem acompanhado do tipo de requisição
    //ou seja, contato_get significa que é uma requisição do tipo GET
    //e o usuario vai requisitar apenas /contato
    public function contato_get(){
        $retorno = [
            'status' => true,
            'nome' => "gabriel",
            'telefone' => '91919191',
            'error' => ''
        ];

        $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
    }

    public function usuario_get(){
        $this->load->model("Usuario_Model");
        $retorno = $this->Usuario_Model->getAllUsers();

        $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
    }

    //usuario_post significa que este metodo vai ser executado
    //quando o WS (web-service) receber uma requisição do tipo
    //POST na url 'usuario'
    public function usuario_post(){
        
        //Primeiro fazemos a validação, para verificar o preenchimento dos campos
        if(!$this->post('email') || (!$this->post('senha'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campos não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'email' => $this->post('email'),
            'senha' => $this->post('senha')
        );

        //carregamos o model, e mandamos inserir no banco
        //os dados informados pelo usuario
        $this->load->model('Usuario_Model');
        if ($this->Usuario_Model->insert($data)){
            //deu certo
            $this->set_response([
                'status' => true,
                'message' => 'Usuario inserido com sucesso!'
            ], REST_Controller_Definitions::HTTP_OK);
        }else{
            //deu errado
            $this->set_response([
                'status' => false,
                'message' => 'Falha ao inserir usuario!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function usuario_delete(){
        $id = (int) $this->get('id');
        if($id > 0){
            $this->load->model('Usuario_Model');
            if($this->Usuario_Model->delete($id)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Usuario deletado com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
                return;
            }else{
                $this->set_response([
                    'status' => false,
                    'message' => 'Falha ao deletar usuario!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Usuario invalido!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function usuario_put(){
        $id = (int) $this->get('id');
        if ($id > 0){
            $this->load->model('Usuario_Model');
            if(!$this->put('email') || (!$this->put('senha'))) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campos não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            $data = array(
                'email' => $this->put('email'),
                'senha' => $this->put('senha')
            );

            if($this->Usuario_Model->alter($id,$data)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Usuario alterado com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
            }else{
                $this->set_response([
                    'status' => true,
                    'message' => 'Falha ao alterar com sucesso!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Usuario invalido!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
    }
}