@extends('templates/main_layout')

@section('main')

    <div class="container">
        <div class="row">
            <div class="col">

                <div class="row align-items-center mb-3">
                    <div class="col">
                        <h4>Tarefas</h4>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('new_task') }}" class="btn btn-primary"><i class="bi bi-plus-square me-2"></i>Nova Tarefa</a>
                    </div>
                </div>

                @if(count($tasks) != 0) <!-- $tasks é uma variável que está chegando a partir do método index na classe main -->

                <table class="table table-striped table-bordered pt-2" id="table_tasks">
                    <thead class="table-dark">
                        <tr>
                            <th class="w-75">Tarefas</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                       <!-- ANTES FUNCIONAVA O FOREACH QUE TRATAVA OS DADOS PARA A MONTAGEM DAS LINHAS COM OS DADOS RECEBIDOS DA COLEÇÃO. AGORA ISTO ESTÁ SENDO REALIZADO PELA BIBLIOTECA DATATABLE -->


                       {{-- @foreach($tasks as $task)

                       <tr>
                           <td></td>
                           <td class="text-center"></td>
                           <td class="text-center"></td>
                       </tr>

                       @endforeach --}}


                       <!-- A BIBLIOTECA DATATABLE SABE QUE PRECISA ATUAR NO TBODY, A PARTIR DO ID DA TABELA. POR ISSO TBODY FICA VAZIO -->



                    </tbody>
                </table>

                @else
                <p class="text-center opacity-50 my-5">Não existem tarefas registradas.</p>
                @endif

            </div>
        </div>
    </div>


    <script>

            // ATENÇÃO: Estamos usando JQuery para injetar conteúdo numa tabela, cujo id foi passado em $('#table_tasks') sendo que estamos aplicando a biblioteca DataTable, que pega a coleção trazida quando a view foi montada e

           // Usando JQuery
           $(document).ready(function(){
            $('#table_tasks').DataTable({
                data: @json($tasks),
                columns: [
                    {data: 'task_name'},
                    {data: 'task_status', className: 'text-center'}, // É possível passar um segundo argumento, para, por exemplo, adicionar uma classe
                    {data: 'task_actions', className: 'text-center'}
                ]
            })
        }) // Lê-se: quando o documento estiver ready, pegue o elemento do id passado, submeta-o ao DataTable que está carregado em head e monte os dados da tabela, passando os nomes das colunas...


    </script>

@endsection
