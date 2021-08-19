<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Carbon\Carbon;

class UsersController extends Controller
{
    # View listagem de usuários
    public function listar()
    {
        return view('users.listar');
    }
    # Ajax do DataTables dos usuários
    public function listarAjax(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return  DataTables::of($data)
                ->addColumn('id', function ($data) {
                    return  $data->id;
                })
                ->addColumn('name', function ($data) {
                    return  $data->name;
                })
                ->addColumn('email', function ($data) {
                    return  $data->email;
                })
                ->addColumn('created_at', function ($data) {
                    return  Carbon::parse($data->created_at)->format('d/m/Y H:i');
                })
                ->addColumn('acoes', function ($data) {
                    return '
                        <div class="btn-group">
                            <a href="' . route('ajax.modal.user') . '" data-id="' . $data->id . '" class="btn btn-info btn-sm modal-call"><i class="fas fa-pencil-alt"></i> Editar</a>
                            <button class="btn btn-danger btn-sm" onclick="confirmThis(\'Você deseja excluir este registro?\',\'' . route('delete.user', ['id' => $data->id]) . '\')"><i class="fas fa-trash"></i> Deletar</button>
                        </div>
                    ';
                })
                ->rawColumns(['created_at', 'acoes'])
                ->make(true);
        }
    }

    # Modal do usuário
    public function modalUser(Request $request)
    {
        $id = isset($request->id) ? $request->id : null;
        $dados = User::find($id);
        return view('users.modal', compact('dados'));
    }
    # Criação do usuário
    public function save(Request $request)
    {
        $exists = User::where('email', '=', $request->email)->first();
        $type = 'success';


        if (empty($request->name) && empty($request->email)) {
            $message = 'Preencha todos os campos.';
            $type = 'error';
        } else {
            if ($request->id) {
                User::find($request->id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
                $message = 'Registro alterado com suceso!';
            } else {
                if ($exists) {
                    $message = 'E-mail já cadastrado.';
                    $type = 'error';
                } else {
                    User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                    ]);
                    $message = 'Usuário cadastrado com sucesso';
                }
            }
        }

        return response()->json(array('message' => $message, 'type' => $type));
    }
    # Exclusão do usuário
    public function deleteUser($id)
    {
        if (isset($id)) {
            $delete = User::find($id)->delete();
            $type = 'success';
            if ($delete) {
                $message = 'Registro deletado com sucesso.';
            } else {
                $type = 'error';
                $message = 'Erro ao deletar registro.';
            }
            return back()->with($type, $message);
        }
    }
}
