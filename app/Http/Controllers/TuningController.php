<?php

namespace App\Http\Controllers;

use App\Tuning;
use Illuminate\Http\Request;

class TuningController extends Controller{

    public function index(Request $request){
        $this->authorize('view', Tuning::class);
        return view('evoque.tuning.index', [
            'vendors' => Tuning::orderBy('type', 'desc')->get()->groupBy('vendor')
        ]);
    }

    public function edit(Request $request, String $type = 'truck', Int $id = null){
        $this->authorize('edit', Tuning::class);
        $tuning = $id ? Tuning::findOrFail($id) : new Tuning();
        if(!$id) $tuning->type = $type;
        if($request->post()){
            $this->validate($request, [
                'vendor' => 'required_if:type,truck|string|nullable',
                'model' => 'required|string',
                'game' => 'required|string',
                'image' => 'image|max:3000',
                'description' => 'nullable|string',
                'type' => 'required|string',
            ]);
            $tuning->fill($request->post());
            $tuning->visible = $request->input('visible') === 'on' ? 1 : 0;
            $tuning->description = htmlentities(trim($request->input('description')));
            if($request->file('image')){
                if($tuning->image && is_file(public_path('images/tuning/'.$tuning->image))){
                    unlink(public_path('images/tuning/'.$tuning->image));
                }
                $tuning->image = $tuning->saveImage($request->file('image'));
            }
            return $tuning->save() ?
                redirect()->route('evoque.tuning')->with(['success' => 'Тюнинг успешно '.($id ? 'изменён!' : 'создан!')]) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tuning->visible = true;
        return view('evoque.tuning.edit', [
            'tuning' => $tuning
        ]);
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', Tuning::class);
        $tuning = Tuning::find($id);
        if($tuning->image && is_file(public_path('images/tuning/'.$tuning->image))){
            unlink(public_path('images/tuning/'.$tuning->image));
        }
        return $tuning->delete() ?
            redirect()->route('evoque.tuning')->with(['success' => 'Тюнинг удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function load(Request $request){
        if($request->ajax()){
            $tuning = Tuning::find($request->input('id'));
            return response()->json([
                'path' => '/images/tuning/'.$tuning->image,
                'status' => 'OK'
            ]);
        }
        return false;
    }

}
