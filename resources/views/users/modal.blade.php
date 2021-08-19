<form action="{{ route('save.user') }}" id="save">
    <input type="hidden" id="id" name="id" value="{{ isset($dados->id) ? $dados->id : null }}">
    <div class="modal-header">
        <h5 class="modal-title">{{ isset($dados->id) ? 'Editar' : 'Novo' }} Usuário</h5>
        <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input name="name" id="name" type="text" class="form-control" value="{{ isset($dados->name) ? $dados->name : '' }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input name="email" id="email" type="text" class="form-control" value="{{ isset($dados->email) ? $dados->email : '' }}">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger modal-close" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
        <button type="button" class="btn btn-success btn-submit">{!! isset($dados->id) ? '<i class="fas fa-pencil-alt"></i> Editar Registro' : '<i class="fas fa-check"></i> Salvar Registro' !!}</button>
    </div>
</form>

<script>
    $(function(){   
        $(".btn-submit").click(function(e) {
            var form = $("#save");
            var action = form.attr('action');
            var params = form.serialize();
			var ajax = new Ajax(action);
			Default.message('Aguarde, processando...', false, 'info');
			ajax.setParams(params);
			ajax.setDataType('json');
			ajax.setSuccess(function(retorno){
				Default.message(retorno['message'], 3000, retorno['type']);
			});
			ajax.execute();
            
            e.preventDefault();
        });
    });
</script>