@extends('templates/main_layout')

@section('main')

    <div class="container">
        <div class="row">
            <div class="col">

                <div class="row align-items-center mb-3">
                    <div class="col">
                        <h4>Tarefas</h4>

                    </div>


                    <div class="col-6 text-center">
                        <form action="{{route('search_submit')}}" method="post">
                            @csrf
                            <div class="d-flex">
                                <input type="text" name="text_search" id="text_search" class="form-control" placeholder="pesquisar">
                                <button class="btn btn-outline-primary ms-3"><i class="bi bi-search"></i></button>

                                <span class="mx-3"></span>
                                <label for="text_search" class="align-self-center me-2">Status</label>
                                <select name="filter" id="filter" class="form-select">
                                    <option value="{{ Crypt::encrypt('all')}}" @php echo (!empty($filter) && $filter == 'all' ? 'selected' : '' /* Ou seja, se a variável $filter não estiver vazia e corresponder ao value deste option, é porque esta opção havia sido selecionada na filtragem. Assim, mantém selected. */) @endphp>Todas</option>
                                    <option value="{{ Crypt::encrypt('new')}}" @php echo (!empty($filter) && $filter == 'new' ? 'selected' : '') @endphp>Nova</option>
                                    <option value="{{ Crypt::encrypt('in_progress')}}" @php echo (!empty($filter) && $filter == 'in_progress' ? 'selected' : '') @endphp>Em progresso</option>
                                    <option value="{{ Crypt::encrypt('canceled')}}" @php echo (!empty($filter) && $filter == 'canceled' ? 'selected' : '') @endphp>Cancelada</option>
                                    <option value="{{ Crypt::encrypt('completed')}}" @php echo (!empty($filter) && $filter == 'completed' ? 'selected' : '') @endphp>Concluído</option>
                                </select>
                            </div>
                        </form>
                    </div>


                    <div class="col text-end">
                        <a href="{{ route('new_task') }}" class="btn btn-primary"><i class="bi bi-plus-square me-2"></i>Nova Tarefa</a>
                    </div>
                </div>

                @if(count($tasks) != 0) <!-- $tasks é uma variável que está chegando a partir do método index na classe main -->

                <table class="table table-striped table-bordered pt-2" id="table_tasks">
                    <thead class="table-primary">
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
                    {data: 'task_status', className: 'text-center align-middle'}, // É possível passar um segundo argumento, para, por exemplo, adicionar uma classe
                    {data: 'task_actions', className: 'text-center align-middle'}
                ]
            })
        }) // Lê-se: quando o documento estiver ready, pegue o elemento do id passado, submeta-o ao DataTable que está carregado em head e monte os dados da tabela, passando os nomes das colunas...


        let filter = document.querySelector('#filter');
        filter.addEventListener('change', () => {
            let value = filter.value;
            window.location.href = "{{ url('/filter') }}" + "/" + value;
        });


    </script>

@endsection
