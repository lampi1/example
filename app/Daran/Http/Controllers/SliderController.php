<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Slider;
use App\Daran\Models\Slide;
use App\Daran\Http\Requests\SlideRequest;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read slider')){
            abort(503);
        }

        return view('daran::sliders.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create slider')){
            abort(503);
        }
        $slider = new Slider();
        return view('daran::sliders.create', compact('slider'));
    }

    public function store(Request $request)
    {
        if(!Auth::user()->can('create slider')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $slider = new Slider($request->all());
        $slider->user_id = Auth::user()->id;
        $slider->save();

        if ($slider->id) {
            return Redirect::route('admin.sliders.edit',['slider' => $slider])->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Request $request, Slider $slider)
    {
        if(!Auth::user()->can('edit slider')){
            abort(503);
        }

        return view('daran::sliders.edit', compact('slider'));
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create slider')){
             abort(503);
         }

         $slider = Slider::findOrFail($id);
         $clone = $slider->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.sliders.edit', ['slider' => $clone])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.sliders.index')->with('error', trans('daran::message.error.clone'));
         }
    }

    public function cloneSlide(Request $request, int $id)
    {
         if(!Auth::user()->can('create slider')){
             abort(503);
         }

         $slide = Slide::findOrFail($id);
         $clone = $slide->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.slides.edit', ['id' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.sliders.edit', ['slider' => $slide->slider])->with('error', trans('daran::message.error.clone'));
         }
    }

    public function addSlide($id_slider, $type)
    {
        if(!Auth::user()->can('edit slider')){
            abort(503);
        }

        $slide = new Slide();
        $slide->type = $type;
        $slide->slider_id = $id_slider;
        return view('daran::sliders.add-slide', compact('slide'));
    }

    public function storeSlide(SlideRequest $request)
    {
        if(!Auth::user()->can('edit slider')){
            abort(503);
        }
        $slide = new Slide($request->except('image','image_xs'));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = Str::slug($slide->title).'_'.uniqid().'.'.$extension;
            $slide->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($slide->title).'_'.uniqid().'.'.$extension;
            $slide->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($slide->title).'_'.uniqid().'.'.$extension;
            $slide->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if($slide->save()){
            return Redirect::route('admin.sliders.edit',['slider' => $slide->slider])->with('success', trans('daran::message.success.create'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function editSlide($id)
    {
        if(!Auth::user()->can('edit slider')){
            abort(503);
        }

        $slide = Slide::findOrFail($id);

        return view('daran::sliders.edit-slide', compact('slide'));
    }

    public function updateSlide(SlideRequest $request, $id)
    {
        if(!Auth::user()->can('edit slider')){
            abort(503);
        }

        $filename = array();

        $slide = Slide::findOrFail($id);
        $slide->update($request->except('image','image_sm'));

        if ($request->hasFile('image')) {
            $filename[] = $slide->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = Str::slug($slide->title).'_'.uniqid().'.'.$extension;
            $slide->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $slide->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($slide->title).'_'.uniqid().'.'.$extension;
            $slide->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if ($slide->save()) {
            return Redirect::route('admin.sliders.edit',['slider'=>$slide->slider])->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

}
