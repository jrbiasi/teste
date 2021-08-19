@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            {{ __('Listagem de Usuários') }}
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-success modal-call" href="{{ route('ajax.modal.user') }}"><i class="fas fa-plus"></i> Novo Usuário</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="dataTable" class="table table-striped table-striped" style="width:100% !important;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Registro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Mensagems de alertas
    @if(Session::has('success'))
        toastr.success('{{  Session::get("success")  }}');
    @elseif( Session::has('error'))
        toastr.error('{{ Session::get("error") }}');
    @endif
    
    // Função responsável para confirmação
    function confirmThis(text, url = null) {
        $.confirm({
            escapeKey: true,
            title: 'Alerta',
            content: text,
            icon: 'fas fa-exclamation',
            closeAnimation: 'opacity',
            opacity: 0.5,
            buttons: {
                'confirm': {
                    text: 'SIM',
                    btnClass: 'btn-success',
                    action: function () {
                        if(!Default.isEmpty(url)) {
                            window.location.assign(url);
                        } else {
                            location.reload();
                        }
                    }
                },
                cancel: {
                    text: 'NÃO',
                    btnClass: 'btn-danger',
                }
            }
        });
    }

    // Renderização do dataTables
    $(function(){   
        $("body").delegate(".modal-call", "click", function(e) {
            var id = $(this).data('id');
            var modal = new Modal();
            var params = {id: id};
            var url = ($(this).data('url')) ? $(this).data('url') : $(this).attr('href');
            var fullscreen = ($(this).data('fullscreen')) ? $(this).data('fullscreen') : false;
            if(fullscreen) {
                modal.setFullscreen(fullscreen);
            }
            modal.setParams(params);
            modal.setUrl(url);
            modal.execute();
            e.preventDefault();
        });   
        $("#dataTable").DataTable({
            language: {
                "url": "https://raw.githubusercontent.com/DataTables/Plugins/master/i18n/pt_br.json",
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ajax.listar') }}",
            },
            pageLength: 5,
            order: [
                [0, "desc" ]
            ],
            lengthMenu: [
                [5, 50, 100, -1],
                [5, 50, 100, 'Tudo']
            ],
            columns: [
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
                {
                    data: 'acoes',
                    name: 'acoes',
                    searchable: false,
                    orderable: false,
                },
            ]
        });
    });

</script>

@endsection
