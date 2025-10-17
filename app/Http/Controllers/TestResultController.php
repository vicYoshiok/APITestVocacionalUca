<?php
// app/Http/Controllers/TestResultController.php

namespace App\Http\Controllers;

use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TestResultController extends Controller
{
    /**
     * Guardar resultados del test
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validar los datos recibidos
            $validated = $request->validate([
                'usuario.nombre' => 'required|string|max:255',
                'usuario.lastname' => 'required|string|max:255',
                'usuario.correo' => 'required|email',
                'usuario.telefono' => 'required|string|max:20',
                'usuario.edad' => 'required|integer|min:1',
                'usuario.escuela' => 'required|string|max:255',
                
                'resultados.R' => 'required|integer',
                'resultados.I' => 'required|integer',
                'resultados.A' => 'required|integer',
                'resultados.S' => 'required|integer',
                'resultados.E' => 'required|integer',
                'resultados.C' => 'required|integer',
                
                'porcentajes.R' => 'required|integer|min:0|max:100',
                'porcentajes.I' => 'required|integer|min:0|max:100',
                'porcentajes.A' => 'required|integer|min:0|max:100',
                'porcentajes.S' => 'required|integer|min:0|max:100',
                'porcentajes.E' => 'required|integer|min:0|max:100',
                'porcentajes.C' => 'required|integer|min:0|max:100',
                
                'respuestas' => 'required|array'
            ]);

            // Crear el resultado del test
            $testResult = TestResult::create([
                'nombre' => $validated['usuario']['nombre'],
                'apellido' => $validated['usuario']['lastname'],
                'correo' => $validated['usuario']['correo'],
                'telefono' => $validated['usuario']['telefono'],
                'edad' => $validated['usuario']['edad'],
                'escuela' => $validated['usuario']['escuela'],
                
                'puntaje_R' => $validated['resultados']['R'],
                'puntaje_I' => $validated['resultados']['I'],
                'puntaje_A' => $validated['resultados']['A'],
                'puntaje_S' => $validated['resultados']['S'],
                'puntaje_E' => $validated['resultados']['E'],
                'puntaje_C' => $validated['resultados']['C'],
                
                'porcentaje_R' => $validated['porcentajes']['R'],
                'porcentaje_I' => $validated['porcentajes']['I'],
                'porcentaje_A' => $validated['porcentajes']['A'],
                'porcentaje_S' => $validated['porcentajes']['S'],
                'porcentaje_E' => $validated['porcentajes']['E'],
                'porcentaje_C' => $validated['porcentajes']['C'],
                
                'respuestas' => $validated['respuestas']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resultados guardados correctamente en la base de datos',
                'data' => [
                    'id' => $testResult->id,
                    'fecha' => $testResult->created_at->format('d/m/Y H:i:s')
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación en los datos',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error al guardar resultados: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los resultados
     */
    public function index(): JsonResponse
    {
        try {
            $results = TestResult::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los resultados'
            ], 500);
        }
    }

    /**
     * Obtener un resultado específico
     */
    public function show($id): JsonResponse
    {
        try {
            $result = TestResult::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resultado no encontrado'
            ], 404);
        }
    }

    /**
 * Obtener estadísticas generales
 */
public function estadisticas(): JsonResponse
    {
        try {
            $totalTests = TestResult::count();
            $promedioEdad = TestResult::avg('edad');
            
            // Conteo por área principal (área con mayor porcentaje)
            $areaCounts = [
                'R' => 0, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0
            ];
            
            // Obtener todos los resultados para calcular área principal
            $results = TestResult::all();
            
            foreach ($results as $result) {
                $porcentajes = [
                    'R' => $result->porcentaje_R,
                    'I' => $result->porcentaje_I,
                    'A' => $result->porcentaje_A,
                    'S' => $result->porcentaje_S,
                    'E' => $result->porcentaje_E,
                    'C' => $result->porcentaje_C,
                ];
                
                // Encontrar área con mayor porcentaje
                $areaPrincipal = array_keys($porcentajes, max($porcentajes))[0];
                $areaCounts[$areaPrincipal]++;
            }
            
            // Escuelas únicas
            $escuelas = TestResult::select('escuela', \DB::raw('count(*) as total'))
                ->groupBy('escuela')
                ->orderBy('total', 'desc')
                ->get();
            
            // Rango de edades
            $rangoEdades = [
                'menor_18' => TestResult::where('edad', '<', 18)->count(),
                '18_25' => TestResult::whereBetween('edad', [18, 25])->count(),
                '26_35' => TestResult::whereBetween('edad', [26, 35])->count(),
                'mayor_35' => TestResult::where('edad', '>', 35)->count(),
            ];
            
            // Últimos 7 días
            $ultimos7Dias = [];
            for ($i = 6; $i >= 0; $i--) {
                $fecha = now()->subDays($i)->format('Y-m-d');
                $count = TestResult::whereDate('created_at', $fecha)->count();
                $ultimos7Dias[$fecha] = $count;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_tests' => $totalTests,
                    'promedio_edad' => $promedioEdad ? round($promedioEdad, 1) : 0,
                    'area_counts' => $areaCounts,
                    'escuelas' => $escuelas,
                    'rango_edades' => $rangoEdades,
                    'ultimos_7_dias' => $ultimos7Dias,
                    'fecha_actualizacion' => now()->toDateTimeString()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en estadísticas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }
/**
 * Eliminar un resultado
 */
    public function destroy($id): JsonResponse
    {
        try {
            $result = TestResult::find($id);
            
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resultado no encontrado'
                ], 404);
            }
            
            $result->delete();

            return response()->json([
                'success' => true,
                'message' => 'Resultado eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el resultado: ' . $e->getMessage()
            ], 500);
        }
    }
}