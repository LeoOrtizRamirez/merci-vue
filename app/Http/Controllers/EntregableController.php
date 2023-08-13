<?php

namespace App\Http\Controllers;

use App\Datatables\EntregableDatatable;
use App\Http\Controllers\Controller;
use App\Models\Entregable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;

class EntregableController extends Controller
{
    public function index(): Response
    {
        $entregables = Entregable::all();
        $user = Auth::user();
        return Inertia::render('Entregable/Index', compact('entregables', 'user'));
    }

    public function datatable(
        Request       $request,
        EntregableDatatable $datatable
    ): JsonResponse {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        return Inertia::render('Entregable/Create');
    }

    public function store(Request $request)
    {
        $entregable = new Entregable;
        $entregable->name = $request->name;
        $entregable->extension = $request->extension;
        $entregable->user_id = Auth::user()->id;
        $entregable->save();
        return redirect()->route('entregables.index');
    }

    public function show(Entregable $entregable)
    {
        return Inertia::render('Entregable/Show', compact('entregable'));
    }

    public function edit(Entregable $entregable)
    {
        return Inertia::render('Entregable/Edit', [
            'entregable' => $entregable
        ]);
    }

    public function update(Request $request, Entregable $entregable)
    {
        $entregable = Entregable::find($entregable->id);
        $entregable->name = $request->name;
        $entregable->nit = $request->nit;
        $entregable->estado_id = $request['estado']['id'];
        $entregable->save();
        return redirect()->route('entregables.index');
    }

    public function destroy(Entregable $entregable)
    {
        $imagePath = public_path('images') . "/entregables/" . Auth::user()->id . "/" . $entregable->url;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $entregable->delete();
        return redirect()->back();
    }

    public function uploadImage(Request $request)
    {
        // validate the image
        /* $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf,doc,rar,zip,xlsx|max:2048',
        ]); */

        // store the image
        $extension = $request->image->extension();
        $imageName = time() . '.' . $extension;

        $request->image->move(public_path('images') . "/entregables/" . Auth::user()->id, $imageName);

        $entregable = new Entregable;
        $entregable->name = $request->name;
        $entregable->extension = $extension;
        $entregable->url = $imageName;
        $entregable->user_id = Auth::user()->id;
        $entregable->save();
        return redirect()->route('entregables.index');
    }

    public function updateImage(Request $request)
    {

        // validate the image
        /* $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf,doc,rar,zip,xlsx|max:2048',
        ]); */

        $entregable = Entregable::find($request->id);
        $entregable->name = $request->name;
        if (isset($request->image)) {
            //Se elimina el archivo actual
            $imagePath = public_path('images') . "/entregables/" . Auth::user()->id . "/" . $entregable->url;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            //Se guarda el nuevo archivo
            $extension = $request->image->extension();
            $imageName = time() . '.' . $extension;
            $request->image->move(public_path('images') . "/entregables/" . Auth::user()->id, $imageName);
            $entregable->extension = $extension;
            $entregable->url = $imageName;
        }

        $entregable->user_id = Auth::user()->id;
        $entregable->save();
        return redirect()->route('entregables.index');
    }
}
