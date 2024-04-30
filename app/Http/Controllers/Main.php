<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
            // 'tasks' => $this->_get_tasks() // Essa variável é a que está levando para a view main os dados para montagem da tabela com uso da biblioteca DataTable. ------> índice usado no início do desenvolvimento. Depois da implantação da search, este índice está sendo colocado em if/else abaixo
        ];


        // Check there is a search (Verifique se há uma pesquisa)
        if(session('search')){ // Se existe search, ou seja, alguém digitou algo na barra de pesquisa
            $data['search'] = session('search');
            $data['tasks'] = $this->_get_tasks(session('tasks')); // Tarefas recebidas do método task_search com session()->put('tasks', $tasks);

            // Clear session ------> Para preparar o index para uma nova possível pesquisa, sem os dados que já foram consumidos e resultados retornados
            session()->forget('search');
            session()->forget('tasks');

        } else if(session('filter')) { // Se existe o filter, ou seja, alguém filtrou pelo status, executa esse bloco

            $data['filter'] = session('filter');
            $data['tasks'] = $this->_get_tasks(session('tasks'));

            session()->forget('filter');
            session()->forget('tasks');

        } else { // Mas se ninguém digitou nada na barra de pesquisa e o acesso é através do mecanismo de login ou retorno para o painel das tasks

             // get all tasks
             $model = new TaskModel();
             $tasks = $model->where('id_user', '=', session('id'))
                             ->whereNull('deleted_at')
                             ->get();
             $data['tasks'] = $this->_get_tasks($tasks); // Anteriormente, o método _get_tasks implementava a consulta ao BD para retornar as tasks. Agora, ele apenas recebe  da consulta acima e apenas organizar para retornar a collection.

        }

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

    private function _get_tasks($tasks) // método privado por que deve ser acessado apenas no âmbito desta classe
    {

        // ------------------------------------------------------------------------
        // DESATIVADO ESTE BLOCO EM FUNÇÃO DE ADAPTAÇÕES REALIZADAS NO MÉTODO INDEX APÓS IMPLEMENTAÇÃO DE SEARCH

        // $model = new TaskModel(); // Classe Model criada com artisan e que recebe dados da tabela tasks
        // $tasks = $model->where('id_user', '=', session()->get('id'))->whereNull('deleted_at')->get(); // Sintaxe da consulta passando o id do usuário que foi colocado a partitr do método login_submit. Busca dados que não foram deletados

        //---------------------------------------------------------------------------

        $collection = []; // ATENÇÃO: antes de uma adaptação, o tasks estava sendo devolvido apenas como uma coleção no formato objeto, que podia ser acessada com -> ao invés de ['']. Como o return é $collection, que é um array, a maneira lá na view que recebe tasks é usando ['chave'].

        foreach($tasks as $task)
        {

            $link_edit = '<a href="' . route('edit_task', ['id' => Crypt::encrypt($task->id) ]) . '" class="btn btn-dark btn-sm m-1"><i class="bi bi-pencil-square p-2"></i></a>';

            $link_delete = '<a href="' . route('delete_task', ['id' => Crypt::encrypt($task->id)]) . '" class="btn btn-dark btn-sm m-1"><i class="bi bi-trash p-2"></i></a>';

            $collection[] = [
                'task_name' => '<span class="task-title">' . $task->task_name . '<br></span><small class="opacity-50">' . $task->task_description . '</small>',
                'task_status' => $this->_status_name($task->task_status), // Pegando de uma coleção pra montar um elemento de outra coleção
                'task_actions' => $link_edit . $link_delete
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
            return '<span class="' . $this->_status_badge($status) . '">' . $status_collection[$status] . '</span>';
        else
            return '<span class="' . $this->_status_badge('Desconhecido') . '">' . "Desconhecido" . '</span>'; // Se tiver apenas uma declaração dentro do if, a sintaxe pode ser realizada assim sem uso de chaves

    }


    private function _status_badge($status)
    {
        $status_collection = [
            'new' => 'badge bg-primary',
            'in_progress' => 'badge bg-success',
            'cancelled' => 'badge bg-danger',
            'completed' => 'badge bg-secondary'
        ];
        if(key_exists($status, $status_collection))
        {
            return $status_collection[$status];
        } else{
            return 'badge bg-secondary';
        }
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
            return redirect()->route('new_task')->withInput()->with('task_error', 'Já existe uma tarefa com o mesmo nome.');
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





    // ----------------------------------
    // EDIT AND DELETE TASK
    // ----------------------------------


    public function edit_task($id)
    {
        try
        {
            $id = Crypt::decrypt($id);
        }
        catch(\Exception $e)
        {
            return redirect()->route('index');
        }
       // var_dump($id); ------> TESTE: O id está chegando corretamente?


       // get task
       $model = new TaskModel();
       $task = $model->where('id', '=', $id)
                     ->first();
                     // Se quiséssemos reforçar:
                     // ->where('id_user', session('id')) // É redundante
                     // ->whereNull('deleted_at')

        // var_dump($task); ------> TESTE: A consulta está retornando os dados corretamente?

        // check if task exists
        if(empty($task))
        {
            return redirect()->route('index');
        }


        $data = [
            'title' => 'Editar Tarefa',
            'task' => $task
        ];

        return view('edit_task_frm', $data);

    }



    public function edit_task_frm(Request $request)
    {
        echo "<pre>";
        print_r($request->all());
    }


    public function edit_task_submit(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all()); ------> Para testes de recebimento de dados do formulário

        // form validate

        $request->validate(

            [
                // Campos requeridos e com no mínimo 3 caracteres
                'text_task_name' => 'required|min:3|max:200',
                'text_task_description' => 'required|min:3|max:1000',
                'text_task_status' => 'required'

            ],
            [
                // Regras para responder a possíveis erros
                'text_task_name.required' => 'O campo é de preenchimento obrigatório',
                'text_task_name.min' => 'Mínimo de :min caracteres',
                'text_task_name.max' => 'Máximo de :max caracteres',
                'text_task_description.required' => 'O campo é de preenchimento obrigatório',
                'text_task_description.min' => 'Mínimo de :min caracteres',
                'text_task_description.max' => 'Máximo de :max caracteres',
                'text_task_status.required' => 'O campo é de preenchimento obrigatório'

            ]
        );


        // get form data
        $id_task = null;
        try{
            $id_task = Crypt::decrypt($request->input('task_id'));
        } catch(\Exception $e){
            return redirect()->route('index'); // Não é esperado este erro. Caso alguém tente alterar o id, será por via de algum mecanismo não permitido e ao, desta forma, ao invés de lançar uma mensagem de erro, lança o usuário diretamente para o index.
        }


        $task_name = $request->input('text_task_name');
        $task_description = $request->input('text_task_description');
        $task_status = $request->input('text_task_status');

        // Teste para verificar se os dados estão chegando corretamente
        // dd([
        //     $task_name,
        //     $task_description,
        //     $task_status
        // ]);


        // Check if there is another task wich the same name and from the same user

        $model = new TaskModel();
        $task = $model->where('id_user', '=', session('id')) // Se o mesmo utilizador
                        ->where('task_name', '=', $task_name) // Se alguma task tiver o mesmo nome do que foi passado no formulário
                        ->where('id', '!=', $id_task) // E se o id for diferente, ou seja, tentando alterar o nome de uma task para o mesmo nome de uma task que já existe (fere a regra de negócio)
                        ->whereNull('deleted_at') // E que não seja uma tarefa eliminada
                        ->first();

        if($task) // Se existe uma task nas condições acima especificadas
        {
            return redirect()->route('edit_task', ['id' => Crypt::encrypt($id_task)])
                            ->with('task_error', 'Já existe outra tarefa com o mesmo nome.');
        }


       // echo "Chegou até aqui.";



       // update task
       $model->where('id', '=', $id_task)
             ->update([
                'task_name' => $task_name,
                'task_description' => $task_description,
                'task_status' => $task_status,
                'updated_at' => date('Y-m-d H:i:s')
             ]);

             return redirect()->route('index');
    }





    public function delete_task($id)
    {




        try
        {
            $id = Crypt::decrypt($id);
        }
        catch(\Exception $e)
        {
            return redirect()->route('index');
        }


        // get task data
         $model = new TaskModel();
         $task = $model->where('id', '=', $id)->first();


        if(!$task) // Se não for encontrado um registro, retorna, mas não é suposto que isto aconteça.
        {
            return redirect()->route('index');
        }


        $data = [
            'title' => 'Excluir tarefa',
            'task' => $task
        ];


        return view('delete_task', $data);



    }



    public function delete_task_confirm($id)
    {
        // echo $id; -----------------> Teste para saber se o parâmetro está chegando corretamente, inclusive, com criptografia
        echo $id_task = Crypt::decrypt($id);

        // delete data (software delete)
        $model = new TaskModel();
        $task = $model->where('id' ,'=', $id_task)
                      ->update([
                        'deleted_at' => date('Y-m-d H:i:s')
                      ]);
                      // ATENÇÃO: a regra de negócio não a de deletar o registro, mas de atualizar o deleted_at

        return redirect()->route('index');

    }






    // ----------------------------------
    // SEARCH AND SORT
    // ----------------------------------


    public function search_submit(Request $request)
    {
       // echo 'search';

       // get data from formn
       $search = $request->input('text_search'); // Informação que vem do campo de pesquisa

       // get tasks
       $model = new TaskModel();
       if($search == '') // Se nada foi digitado na barra de pesquisa, string vazia
       {
            // Busque todas as tarefas do usuário
            $tasks = $model->where('id_user', '=', session('id'))
                           ->whereNull('deleted_at')
                           ->get();
       } else { // Se foi digitado algo na barra de pesquisa
            $tasks = $model->where('id_user', '=', session('id'))
                           ->where('task_name', 'like', '%' . $search . '%') // Ou seja, se o campo com o nome da task contiver, em qualquer ponto da string, o que foi passado na barra de pesquisa
                           ->orWhere('task_description', 'like', '%' . $search . '%') // Cláusula OR aplicada, por que basta que ocorra em task_name ou task_description
                            ->whereNull('deleted_at')
                            ->get();
       }

       // put tasks in session
       session()->put('tasks', $tasks);
       session()->put('search', $search); // Enviando também o termo pesquisado

       return redirect()->route('index');

    }



    public function filter($status)
    {



       // decerytp status
       try{
        $status = Crypt::decrypt($status);
       } catch(\Exception $e){
            return redirect()->route('index');
       }

       // echo $status; ----> teste



       // get tasks
       $model = new TaskModel();
       if($status == 'all'){
        $tasks = $model->where('id_user', '=', session('id'))
                        ->whereNull('deleted_at')
                        ->get(); // Se o status é all, significa que o filtro não precisa agir buscando um tipo específico de status, mas todas as tasks


       } else {
        $tasks = $model->where('id_user', '=', session('id'))
                        ->where('task_status', '=', $status)
                        ->whereNull('deleted_at')
                        ->get(); // Se foi selecionado um status específico, a consulta deve retornar os dados do status escolhido
       }


       session()->put('tasks', $tasks);
       session()->put('filter', $status); // Será utilizado em main.blade para manter selected o status escolhido

       return redirect()->route('index');


    }



}
