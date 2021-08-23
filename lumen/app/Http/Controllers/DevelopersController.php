<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class DevelopersController extends Controller
{
    public function index()
    {
        $filter = request('q');

        $query = Developer::query();

        if (request('nome')) {
            $query->where('nome', 'like', '%' . request('nome') . '%');
        }
        if (request('idade')) {
            $query->where('idade', '=', request('idade'));
        }
        if (request('datanascimento')) {
            $query->where('datanascimento', '=', request('datanascimento'));
        }
        if (request('sexo')) {
            $query->where('sexo', '=', request('sexo'));
        }
        if (request('hobby')) {
            $query->where('hobby', 'like', '%' . request('hobby') . '%');
        }

        $paginate = $query->latest()
            ->paginate(10)
            ->toArray();

        if ((request('nome') || request('idade') || request('datanascimento') || request('sexo') || request('hobby')) && !count($paginate['data'])) {
            $data = array_merge(
                ['message' => 'Desculpe, nenhum desenvolvedor encontrado'],
                $paginate
            );
            $status = 404;
        } else {
            $data = array_merge(
                ['message' => 'Desenvolvedores encontrados com sucesso!'],
                $paginate
            );
            $status = 200;
        }

        return response()->json($data, $status);
    }

    public function show($id)
    {
        if (!$developer = Developer::find($id)) {
            return response()->json([
                'message' => 'Desenvolvedor não encontrado!'
            ], 404);
        }

        return response()->json([
            'message' => 'Desenvolvedor encontrado com sucesso!',
            'data' => $developer
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->makeValidator($request);
        if ($validator->fails()) {
            return $this->failValidationResponse($validator);
        }

        $datanascimento = Carbon::parse($request->datanascimento);
        $idade = $datanascimento->diffInYears(Carbon::now());

        $data = array_merge(
            $request->only('nome', 'sexo', 'hobby'),
            [
                'idade' => $idade,
                'datanascimento' => $datanascimento
            ]
        );

        if (!$developer = Developer::create($data)) {
            abort(500, "Algo deu errado ao criar o desenvolvedor");
        }

        return response()->json([
            'message' => 'Desenvolvedor criado com sucesso!',
            'id' => $developer->id
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!$developer = Developer::find($id)) {
            return response()->json([
                'message' => 'Desenvolvedor não encontrado!'
            ], 404);
        }

        $validator = $this->makeValidator($request);
        if ($validator->fails()) {
            return $this->failValidationResponse($validator);
        }

        $datanascimento = Carbon::parse($request->datanascimento);
        $idade = $datanascimento->diffInYears(Carbon::now());

        $data = array_merge(
            $request->only('nome', 'sexo', 'hobby'),
            [
                'idade' => $idade,
                'datanascimento' => $datanascimento
            ]
        );

        if (!$developer->update($data)) {
            abort(500, "Algo deu errado ao criar o desenvolvedor");
        }

        return response()->json([
            'message' => 'Desenvolvedor atualizado com sucesso!'
        ]);
    }

    public function delete($id)
    {
        if (!$developer = Developer::find($id)) {
            return response()->json([
                'message' => 'Desenvolvedor não encontrado!'
            ], 404);
        }

        if (!$developer->delete()) {
            abort(500, "Algo deu errado ao remover o desenvolvedor");
        }

        return response()->json([
            'message' => 'Desenvolvedor removido com sucesso!'
        ]);
    }

    public function deleteMultiple(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ]);

        if (!Developer::whereIn('id', explode(',', $request->ids))->delete()) {
            abort(500, "Algo deu errado ao remover os desenvolvedores");
        }

        return response()->json([
            'message' => 'Desenvolvedores removidos com sucesso!'
        ]);
    }

    private function failValidationResponse(Validator $validator)
    {
        return response()->json([
            'message' => 'Não foi possível criar o desenvolvedor, verifique todos os campos',
            'errors' => $validator->getMessageBag()
        ], 400);
    }

    private function makeValidator(Request $request)
    {
        return \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nome' => 'required|max:255',
            'sexo' => 'required|in:' . Developer::sexoTypes()->join(','),
            'hobby' => 'required',
            'datanascimento' => 'required|date_format:Y-m-d',
        ], [
            'nome.required' => 'O campo nome é obrigatório',
            'nome.max' => 'O campo nome não deve ter mais que 255 caracteres',
            'sexo.required' => 'O campo sexo é obrigatório',
            'sexo.in' => 'O campo sexo é inválido, deve ser uma das seguintes opções: ' . Developer::sexoTypes()->join(','),
            'hobby.required' => 'O campo hobby é obrigatório',
            'datanascimento.required' => 'O campo data de nascimento é obrigatório',
            'datanascimento.date_format' => 'O campo data de nascimento é inválido, deve estar no formato ano-mês-dia',
        ]);
    }
}
