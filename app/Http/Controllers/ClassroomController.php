<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomStoreRequest;
use App\Http\Requests\ClassroomUpdateRequest;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $classrooms = Classroom::withTrashed()->get();
        $buildings = ['Informática', 'Economía', 'Humanidades', 'Aulas comunes', 'Biblioteca'];
        $types = ['Laboratorio', 'Aula común'];
        return view('classroom.index', compact('classrooms', 'buildings', 'types'));
    }

    /**
     * Show the form for creating a new resource
     *
     */
    public function create()
    {
        return view('classroom.create');
    }

    /**
     * Store a newly created resource in storage.
     *TODO: $request->all() dentro de create
     */
    public function store(ClassroomStoreRequest $request)
    {
        try {
            $classroom = Classroom::create([
                'classroom_name' => $request->classroom_name,
                'location' => $request->location,
                'capacity' => $request->capacity,
                'type' => $request->type,
                'building' => $request->building,
            ]);
            $classroom->save();

            if ($request->building == 'Informática') {
                $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                foreach ($days as $day) {
                    Schedule::create([
                        'day' => $day,
                        'start_time' => '08:00:00',
                        'finish_time' => '20:00:00'
                    ])->save();
                }

            }
            flash('Se ha añadido un nuevo aula con éxito')->success();
            return redirect(route('classrooms.index'));
        } catch (\Exception $e) {
            flash('Ha ocurrido un error al crear un nuevo aula')->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show(Classroom $classroom)
    {
        return view('classroom.show', compact('classroom'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit(Classroom $classroom)
    {
        return view('classroom.edit', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(ClassroomUpdateRequest $request, Classroom $classroom)
    {
        try {
            $classroom->update($request->all());
            flash('Se ha actualizado el aula con éxito')->success();
            return redirect(route('classrooms.index'));
        } catch (\Exception $e) {
            flash('Ha ocurrido un error al actualizar el aula')->error();
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(Classroom $classroom)
    {
        try {
            $classroom->delete();
            flash('Se ha deshabilitado correctamente el aula')->success();
            return redirect(route('classrooms.index'));
        } catch (\Exception $e) {
            flash('Ha ocurrido un error al deshabilitar el aula')->error();
            return back();
        }
    }

    /**
     * Se encarga de revivir el aula
     *
     */

    public function activateClassroom($id)
    {
        $classroom = Classroom::withTrashed()->where('id', $id)->restore();
        flash('Aula habilitada correctamente')->success();
        return back();
    }
}
