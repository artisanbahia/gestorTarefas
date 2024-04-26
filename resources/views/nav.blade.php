<div class="bg-black text-white mb-5">
    <div class="container-fluid">
        <div class="row align-itens-center">
            <div class="col p-3">
                <h3 class="text-primary">Gestor de Tarefas</h3>
            </div>
            <div class="col p-3 text-end">
                <span><i class="bi bi-person"></i> {{ session()->get('username') }}</span>
                <span class="mx-3"><i class="bi bi-three-dots-vertical opacity-50"></i></span>
                <span><a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right m-2"></i>Sair</i></a></span>

            </div>
        </div>
    </div>

</div>
