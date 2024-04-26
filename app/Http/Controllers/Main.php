<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{

    // ----------------------------------
    // MAIN PAGE
    // ----------------------------------
    public function index()
    {
        $data = [
            'title' => 'Gestor de Tarefas',
            'datatables' => true, //
            'tasks' => $this->_get_tasks() // Essa variável é a que está levando para a view main os dados para montagem da tabela com uso da biblioteca DataTable.
        ];

        return view('main', $data);
    }



    // ----------------------------------
    // LOGIN
    // ----------------------------------

    public function login()
    {
        $data = [
            'title' => 'Login'
        ];
        return view('login_frm', $data);
    }



    public function login_frm()
    {
        session()->put('username', 'admin'); // Maneira de setar (colocar) uma variável
        echo "usuário Logado";
    }



    public function login_submit(Request $request) // Request se torna um objeto contendo todos os 'names' que são passados junto à requisição feita ao formulário
    {
        // form validation
        $request->validate(
            [
                // Campos requeridos e com no mínimo 3 caracteres
                'text_username' => 'required|min:3',
                'text_password' => 'required|min:3'

            ],
            [
                // Regras para responder a possíveis erros
                'text_username.required' => 'O campo é de preenchimento obrigatório',
                'text_username.min' => 'O login deve ter ao menos 3 caracteres',
                'text_password.required' => 'O campo é de preenchimento obrigatório',
                'text_password.min' => 'A senha deve ter ao menos 3 caracteres'
            ]
        );

        // echo "Formulário validado com sucesso"; ------> TESTE


        // Get form data

        $username = $request->input('text_username');
        $password = $request->input('text_password');

        // Check if user exists

        $model = new UserModel(); // Se associa a tabela de users
        $user = $model->where('username', '=', $username)->whereNull('deleted_at')->first(); // busque o primeiro registro em que o username é igual ao username do formulário e que deleted_at é null, significando que não é um usuário considerado deleted.

        if($user) // Se a consulta retornar true, ou seja, há um usuário com o username passado e que não é deleted
        {

            // Check if password is correct

            if($password === $user->password) // Se o password digitado no formulário é igual ao password da tupla capturada em $user


                // --------------------------------------------------------------------------------------
                //
                // ATENÇÃO
                //
                // SINTAXE USADA POR JOÃO RIBEIRO -----> if(password_verify($password, $user->password))
                // O uso do password_verify não estava retornando corretamente
                // Por isso, utilizei uma comparação direta e funcionou.
                // Investigar melhor o uso da função.
                //
                //---------------------------------------------------------------------------------------
            {

                $session_data = [
                     'id' => $user->id,
                     'username' => $user->username
                 ]; // Cria duas variáveis para a sessão com campos id e username da tupla

                 session()->put($session_data); // Colocando o array na sessão

                return redirect()->route('index'); // Redireciona para a rota index, que é a '/', mas nomeada como index, que, por sua vez, monta a view main

            }

        }else {

        // Invalid login

       return redirect()->route('login')->with('login_error', 'Login inválido'); // Caso os dados passados não retornem true para execução do bloco de login, retorna para  tela de login e com (with) mensagem de erro.

        }
    }




    // ----------------------------------
    // LOGOUT
    // ----------------------------------

    public function logout()
    {
        session()->forget('username'); // Esqueça a variável username
        return redirect()->route('login'); // Redirecione para  página de login
    }




    // ----------------------------------
    // TAREFAS
    // ----------------------------------

    private function _get_tasks() // método privado por que deve ser acessado apenas no âmbito desta classe
    {
        $model = new TaskModel(); // Classe Model criada com artisan e que recebe dados da tabela tasks
        $tasks = $model->where('id_user', '=', session()->get('id'))->whereNull('deleted_at')->get(); // Sintaxe da consulta passando o id do usuário que foi colocado a partitr do método login_submit. Busca dados que não foram deletados

        $collection = []; // ATENÇÃO: antes de uma adaptação, o tasks estava sendo devolvido apenas como uma coleção no formato objeto, que podia ser acessada com -> ao invés de ['']. Como o return é $collection, que é um array, a maneira lá na view que recebe tasks é usando ['chave'].

        foreach($tasks as $task)
        {

            $link_edit = '<a href="' . route('edit_task', ['id' => $task->id]) . '" class="btn btn-danger btn-sm m-1"><i class="bi bi-pencil-square p-2"></i></a>';

            $link_delete = '<a href="' . route('delete_task', ['id' => $task->id]) . '" class="btn btn-dark btn-sm m-1"><i class="bi bi-trash p-2"></i></a>';

            $collection[] = [
                'task_name' => $task->task_name,
                'task_status' => $this->_status_name($task->task_status), // Pegando de uma coleção pra montar um elemento de outra coleção
                'task_actions' => $link_delete . $link_edit
            ];
        }



        return $collection;


    }



    private function _status_name($status)
    {

        $status_collection = [
            'new' => 'Nova',
            'in_progress' => 'Em progresso',
            'cancelled' => 'Cancelada',
            'completed' => 'Concluída'
        ];

        if(key_exists($status, $status_collection))
            return $status_collection[$status]; // Usa-se o [] e não o () porque estou recebendo o valor de um array
        else
            return "Desconhecido"; // Se tiver apenas uma declaração dentro do if, a sintaxe pode ser realizada assim sem uso de chaves

    }










    public function new_task()
    {       $data = [
            'title' => 'Nova Tarefa'
        ];
        return view('new_task_frm', $data);
    }







    public function new_task_submit(Request $request)
    {
        // form validation
        $request->validate(

            [
                // Campos requeridos e com no mínimo 3 caracteres
                'text_task_name' => 'required|max:200',
                'text_task_description' => 'required|min:3|max:1000'

            ],
            [
                // Regras para responder a possíveis erros
                'text_task_name.required' => 'O campo é de preenchimento obrigatório',
                'text_task_name.max' => 'Máximo de :max caracteres',
                'text_task_description.required' => 'O campo é de preenchimento obrigatório',
                'text_task_description.min' => 'Mínimo de :min caracteres',
                'text_task_description.max' => 'Máximo de :max caracteres'

            ]
        );

        // get form data

        $task_name = $request->input('text_task_name');
        $task_description = $request->input('text_task_description');

        // Check if there is already another task with the same name for the same user
        // Verifique se já existe outra tarefa com o mesmo nome para o mesmo usuário

        $model = new TaskModel(); // Lembrando que taskmodel é uma classe criada com artisan e que passa o nome da tabela para operações no banco de dados
        $task = $model->where('id_user', '=', session('id'))
              ->where('task_name', '=', $task_name)
              ->whereNull('deleted_at')
              ->first();
              // ou seja, buscar o primeiro registro encontrado (first) que seja do usuário do id passado, que tenha o mesmo nome do campo recebido do formulário e que seja null no campo deleted_at

        if($task) // Se a consulta anterior for true, ou seja, tem uma tarefa com o mesmo nome, do mesmo usuário e não deletada, volta para o formulário e apresenta um erro
        {
            return redirect()->route('new_task')->with('task_error', 'Já existe uma tarefa com o mesmo nome.');
        }

        // Passsou até aqui? Então grava a tarefa no formulário


        // Insert new task
        $model->id_user = session('id');
        $model->task_name = $task_name;
        $model->task_description = $task_description;
        $model->task_status = 'new';
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();

        return redirect()->route('index');
    }

}


