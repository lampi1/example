<?php

namespace App\Daran\Http\Controllers\Api;

use App\Daran\Models\Form;
use App\Daran\Models\FormResult;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function getForms(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');
        $sort_array = explode('|',$sort);

        $qb = Form::select('id','name','created_at')->when($request->filled('lang'),function($q) use($request){
            return $q->where('locale',$request->lang);
        });

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('name','like','%'.$request->get('q').'%');
        });

        if(count($sort_array) == 2){
            $qb->orderBy($sort_array[0],$sort_array[1]);
        }

        if ($limit) {
            $paginator = $qb->paginate($limit);
            $links = array(
                'pagination' => array(
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'self_page_url' => $paginator->url($paginator->currentPage()),
                    'prev_page_url' => $paginator->previousPageUrl(),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'from' => ($paginator->currentPage()-1) * $paginator->perPage() + 1,
                    'to' => ($paginator->currentPage() * $paginator->perPage()) > $paginator->total() ? $paginator->total() : $paginator->currentPage() * $paginator->perPage()
                )
            );
            $items = $paginator->items();//CounterpartResource::collection($qb->paginate($limit));
        } else {
            $links = array();
            $items = $qb->get();
        }

        return response()->json([
            'links' => $links,
            'data' => $items,
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete form')){
        //     abort(503);
        // }
        $form = Form::findOrFail($id);
        if ($form->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function show($id)
    {
        $item = Form::findOrFail($id);

        return response()->json([
            'result' => 'ok',
            'form' => $item
        ]);

    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'formData' => 'required',
            'locale' => 'required|max:2',
            'locale_group' => 'required',
        ];

        $validation = Validator::make($request->all(),$rules);

        if($validation->fails()){
            return $validation->errors();
        }

        $tmp = ($request->formId > 0) ? Form::findOrFail($request->formId) : new Form();
        $tmp->name = $request->name;
        $tmp->template = json_encode($request->formData);
        $tmp->locale = $request->locale;
        $tmp->locale_group = $request->locale_group;
        $tmp->save();

        return response()->json([
            'result' => 'ok',
        ]);
    }

    public function compile(Request $request)
    {
        $rules = [
            'invitationId' => 'required',
            'formData' => 'required',
        ];

        $validation = Validator::make($request->all(),$rules);

        if($validation->fails()){
            return $validation->errors();
        }

        //registro i risultati e aggiorno invitation
        $invitation = Invitation::findOrFail($request->invitationId);
        if($invitation->answered_at){
            return response()->json([
                'result' => 'ko',
                'error' => 'Hai già partecipato a questo Progetto'
            ]);
        }

        $invitation->answered_at = now();
        $invitation->save();

        //verifico non esiste già una risposta
        $count = FormResult::where('form_id',$invitation->project->form_id)->where('invitation_id',$invitation->id)->count();
        if($count > 0){
            return response()->json([
                'result' => 'ko',
                'error' => 'Hai già partecipato a questo Progetto'
            ]);
        }
        $results = new FormResult();
        $results->form_id = $invitation->project->form_id;
        $results->invitation_id = $invitation->id;
        $results->data = json_encode($request->formData);

        if($results->save()){
            return response()->json([
                'result' => 'ok'
            ]);
        }else{
            return response()->json([
                'result' => 'ko',
                'error' => 'Impossibile salvare'
            ]);
        }
    }

}
