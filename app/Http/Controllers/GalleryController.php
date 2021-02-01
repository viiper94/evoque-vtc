<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller{

    public function index(){
        $photos = Gallery::with('user', 'user.member');
        if(Auth::guest() || Auth::user()->cant('toggle', Gallery::class)){
            $photos = $photos->where('visible', '1');
        }
        return view('gallery.index', [
            'photos' => $photos->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function add(Request $request){
        $this->authorize('create', Gallery::class);
        if($request->post()){
            $this->validate($request, [
                'image' => 'required|image',
            ]);
            $image = new Gallery();
            if($file = $request->file('image')){
                $img = new Image();
                $img->load($file->getPathName());
                $name = md5(time().$file->getClientOriginalName()).'.jpg';
                $name_thumb = md5(time().$file->getClientOriginalName()).'_thumb.jpg';
                $img->save(public_path('/images/gallery/').$name);
                $img->resizeToHeight(300);
                $img->save(public_path('/images/gallery/').$name_thumb, IMAGETYPE_JPEG, 70);
                $image->image_full = $name;
                $image->image_thumb = $name_thumb;
                $image->author = $request->post('author');
                $image->uploaded_by = Auth::id();
                $image->on_main = false;
                if(Auth::user()->can('forceCreate', Gallery::class)){
                    $image->visible = $request->post('public') === 'on';
                }
            }
            return $image->save() ?
                redirect()->route('gallery')->with(['success' => 'Скриншот успешно загружен'. ($image->visible ? '!' : ' и будет отображен в галерее после модерации!')]) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('gallery.add');
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', Gallery::class);
        $image = Gallery::findOrFail($id);
        if(is_file(public_path('/images/gallery/'.$image->image_full))){
            unlink(public_path('/images/gallery/'.$image->image_full));
        }
        if(is_file(public_path('/images/gallery/'.$image->image_thumb))){
            unlink(public_path('/images/gallery/'.$image->image_thumb));
        }
        return $image->delete() ?
            redirect()->route('gallery')->with(['success' => 'Скриншот успешно удалён!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function manage(){
        $this->authorize('update', Gallery::class);
        return view('gallery.manage', [
            'photos' => Gallery::with('user', 'user.member')->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function toggle(Request $request, $id){
        $this->authorize('toggle', Gallery::class);
        $image = Gallery::findOrFail($id);
        $image->visible = !$image->visible;
        return $image->save() ?
            redirect()->route('gallery')->with(['success' => 'Скриншот успешно '. ($image->visible ? 'опубликован!' : 'скрыт!')]) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
