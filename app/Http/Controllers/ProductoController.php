<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('created_at', 'desc')->get();
        return view('productos.productos', [
            'productos'=>$productos
        ]);
    }

    public function create()
    {
        $clientes = Cliente::all();

        return view('productos.crear-producto',[
            'clientes'=>$clientes
        ]);
    }

    public function update(Producto $producto)
    {
        $clientes = Cliente::all();

        return view('productos.actualizar-producto',[
            'producto'=> $producto,
            'clientes'=> $clientes
        ]);
    }

}
