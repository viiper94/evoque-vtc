<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\DLC;
use Illuminate\Http\Request;

class DLCController extends Controller{

    public function index(){
        $this->authorize('editDLCList', Convoy::class);
        return view('evoque.convoys.dlc', [
            'dlc_list' => DLC::orderBy('sort')->get()
        ]);
    }

    public function edit(Request $request){
        $this->authorize('editDLCList', Convoy::class);
        $id = $request->post('id');
        $dlc = $id !== null ? DLC::findOrFail($id) : new DLC();
        $this->validate($request, [
            'title' => 'required|string',
            'game' => 'required|string',
            'steam_link' => 'nullable|url'
        ]);
        $dlc->fill($request->post());
        return $dlc->save() ?
            redirect()->back()->with(['success' => 'DLC успешно '. ($id !== false ? 'отредактировано!' : 'добавлено!')]) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function delete(Request $request, $id){
        $this->authorize('editDLCList', Convoy::class);
        $dlc = DLC::findOrFail($id);
        return $dlc->delete() ?
            redirect()->back()->with(['success' => 'DLC удалено!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function sort(Request $request){
        if($request->ajax()){
            foreach($request->post('data') as $sort => $id){
                $dlc = DLC::whereId($id)->update(['sort' => $sort]);
            }
            return response()->json('OK');
        }
        return redirect()->route('evoque.convoys.dlc');
    }

}
