<?php

namespace App\Http\Controllers;

use App\RpReport;
use App\RpReward;
use Illuminate\Http\Request;

class RpRewardController extends Controller{

    public function edit(Request $request, $id = null){
        $this->authorize('updateRewards', RpReport::class);
        $reward = $id ? RpReward::findOrFail($id) : new RpReward();
        if($request->post()){
            $this->validate($request, [
                'stage' => 'required|numeric',
                'km' => 'required|numeric',
                'reward' => 'required|string',
                'game' => 'required|string',
            ]);
            $reward->fill($request->input());
            return $reward->save() ?
                redirect()->route('evoque.rp', )->with(['success' => 'Награда успешно отредактирована!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.rp.edit_reward', [
            'reward' => $reward
        ]);
    }

    public function delete(Request $request, $id = null){
        $this->authorize('updateRewards', RpReport::class);
        $reward = RpReward::findOrFail($id);
        return $reward->delete() ?
            redirect()->back()->with(['success' => 'Награда успешно удалена!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
