<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveFileRequest;
use App\Http\Requests\UpdateFileRequest;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    //Obtener los usuarios paginados para las vistas
    public function index(Request $request)
    {
        $query = File::query();
        $sortBy = $request->input('column');
        $order = $request->input('order');

        if ($request->has('name')) {
            $nameQuery = $request->input('name');
            $query->where('name', 'like', "%{$nameQuery}%");
        }

        if ($request->has('size')) {
            $sizeQuery = $request->input('size');
            $query->where('size', 'like', "%{$sizeQuery}%");
        }

        if ($request->has('type')) {
            $typeQuery = $request->input('type');
            $query->where('type', 'like', "%{$typeQuery}%");
        }

        if ($request->has('type')) {
            $typeQuery = $request->input('type');
            $query->where('type', 'like', "%{$typeQuery}%");
        }

        if ($request->has('date')) {
            $dateQuery = $request->input('date');
            $query->whereDate('created_at', $dateQuery);
        }

        //Odernar la tabla
        if($sortBy) {
            if($sortBy === 'updated_by'){
                $query->with(['user' => function ($query) use ($order) {
                    $query->orderBy('name', $order);
                }])->orderBy($sortBy, $order);  
            }else{
                $query->orderBy($sortBy, $order);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        // Obtener los resultados paginados
        $files = $query->paginate($request->pagination);

        return $files;
    }

    public function store(SaveFileRequest $request)
    {
        DB::beginTransaction();

        foreach ($request->file('files') as $index => $item) {
            $name = time() . '_' . $index + 1 . '.' . $item->getClientOriginalExtension();
            $item->storeAs('public', $name);

            $user = User::where('email', $request->email)->first();
            $fileSizeInBytes = $item->getSize(); // Obtiene el tamaño del archivo en bytes
            $fileSizeInMB = round($fileSizeInBytes / 1024 / 1024, 2); // Convertir a MB y redondear a 2 decimales

            try {
                $file = [
                    'name' => $name,
                    'size' => $fileSizeInMB,
                    'type' => $item->getClientOriginalExtension(),
                    'created_at' => now(),
                    'updated_by' => $user->id,
                ];
                File::create($file);

            } catch (\Illuminate\Validation\ValidationException $validationException) {
                if (!empty($validationException->errors())) {
                    $errors[] = [
                        'fila' => $index,
                        'error' => $validationException->errors(),
                    ];
                }
            }
        }

        if (!empty($errors)) {
            DB::rollback();

            return response()->json(['errors' => $errors], 422);
        }
    
        DB::commit();
        return response()->json(['message' => 'Archivos guardados correctamente']);
    }

    public function show($id)
    {
        $file = File::findOrFail($id);
        return response()->json($file);
    }

    public function update(UpdateFileRequest $request, $id)
    {      
        $file = File::find($id);
    
        if (!$file) {
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }

        $nameCleaned = preg_replace('/[^A-Za-z0-9]/', '', $request->name); //Limpiar el nombre sin espacios ni caracteres especiales
        $newName = $nameCleaned.'.'.$request->type;

        $oldPath = 'public/files/'.$file->name;
        $newPath = 'public/files/'.$newName;

        Storage::move($oldPath, $newPath); //Renombrar el archivo

        $data = [
            'name' => $newName,
            'updated_by' => $request->updated_by,
        ];

        $file->update($data); //Actualiza el registro

        return response()->json(['message' => 'Archivos guardados correctamente']);
    }

    public function destroy($id)
    {
        $file = File::find($id);
    
        if (!$file) {
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
        Storage::delete('public/files/' . $file->name); //Elimina el archivo 
        $file->delete(); //Elimina el registro

        return response()->json(['message' => 'Archivo eliminado exitosamente'], 200);
    }  
}