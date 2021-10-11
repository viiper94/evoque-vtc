<?php

namespace App\Http\Controllers;

use App\TrucksTuning;
use Illuminate\Http\Request;

class TrucksTuningController extends Controller{

    public function index(Request $request){
        $this->authorize('view', TrucksTuning::class);
        $tunings = TrucksTuning::select();
        if($request->input('q')){
            $tunings = $tunings->where('vendor', 'like', $request->input('q').'%')
                ->orWhere('model', 'like', '%'.$request->input('q').'%');
        }
        return view('evoque.trucks_tuning.index', [
            'vendors' => $tunings->get()->groupBy('vendor')
        ]);
    }

    public function add(Request $request){
        $this->authorize('add', TrucksTuning::class);
        $tuning = new TrucksTuning();
        if($request->post()){
            $this->validate($request, [
                'vendor' => 'required|string',
                'model' => 'required|string',
                'game' => 'required|string',
                'image' => 'required|image',
                'description' => 'nullable|string',
            ]);
            $tuning->fill($request->post());
            $tuning->visible = $request->input('visible') === 'on' ? 1 : 0;
            $tuning->description = htmlentities(trim($request->input('description')));
            if($request->file('image')){
                $tuning->image = $tuning->saveImage($request->file('image'));
            }
            return $tuning->save() ?
                redirect()->route('evoque.tuning')->with(['success' => 'Тюнинг успешно создан!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tuning->visible = true;
        return view('evoque.trucks_tuning.edit', [
            'tuning' => $tuning
        ]);
    }

    public function edit(Request $request, $id){
        $this->authorize('edit', TrucksTuning::class);
        $tuning = TrucksTuning::find($id);
        if($request->post()){
            $this->validate($request, [
                'vendor' => 'required|string',
                'model' => 'required|string',
                'game' => 'required|string',
                'image' => 'required|image',
                'description' => 'nullable|string',
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
                redirect()->route('evoque.tuning')->with(['success' => 'Тюнинг успешно изменён!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $tuning->visible = true;
        return view('evoque.trucks_tuning.edit', [
            'tuning' => $tuning
        ]);
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', TrucksTuning::class);
        $tuning = TrucksTuning::find($id);
        if($tuning->image && is_file(public_path('images/tuning/'.$tuning->image))){
            unlink(public_path('images/tuning/'.$tuning->image));
        }
        return $tuning->delete() ?
            redirect()->route('evoque.tuning')->with(['success' => 'Тюнинг удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function load(Request $request){
        if($request->ajax()){
            $tuning = TrucksTuning::find($request->input('id'));
            return response()->json([
                'path' => '/images/tuning/'.$tuning->image,
                'status' => 'OK'
            ]);
        }
        return false;
    }

}
