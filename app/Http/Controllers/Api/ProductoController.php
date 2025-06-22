<?php

   namespace App\Http\Controllers\Api;

   use App\Http\Controllers\Controller;
   use App\Models\Producto;
   use Illuminate\Http\Request;

   class ProductoController extends Controller
   {
       public function index(Request $request)
       {
           $query = Producto::query()->with('categoria');

           if ($request->filled('search')) {
               $query->where('nombre', 'like', '%' . $request->search . '%');
           }

           if ($request->filled('category')) {
               $query->where('categoria_id', $request->category);
           }

           if ($request->filled('stock')) {
               if ($request->stock == 'low') {
                   $query->where('stock', '<=', 10);
               } elseif ($request->stock == 'medium') {
                   $query->whereBetween('stock', [11, 50]);
               } elseif ($request->stock == 'high') {
                   $query->where('stock', '>', 50);
               }
           }

           $productos = $query->paginate(9);

           return response()->json($productos);
       }

       public function show($id)
       {
           $producto = Producto::with('categoria')->findOrFail($id);
           return response()->json($producto);
       }
   }