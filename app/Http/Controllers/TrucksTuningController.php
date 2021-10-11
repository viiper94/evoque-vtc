<?php

namespace App\Http\Controllers;

use App\TrucksTuning;
use Illuminate\Http\Request;

class TrucksTuningController extends Controller{

    public function index(Request $request){
        // insert policy here
        $tunings = TrucksTuning::select();
        if($request->input('q')){
            $tunings = $tunings->where('vendor', 'like', $request->input('q').'%')
                ->orWhere('model', 'like', '%'.$request->input('q').'%');
        }
        return view('evoque.trucks_tuning.index', [
            'tunings' => $tunings->get()
        ]);
    }

    public function add(Request $request){
        // insert policy here
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
        // insert policy here
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
        // insert policy here
        $tuning = TrucksTuning::find($id);
        if($tuning->image && is_file(public_path('images/tuning/'.$tuning->image))){
            unlink(public_path('images/tuning/'.$tuning->image));
        }
        return $tuning->delete() ?
            redirect()->route('evoque.tuning')->with(['success' => 'Тюнинг удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
