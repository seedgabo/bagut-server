<?php

namespace App\Http\Controllers;

use App\Models\InvoiceProveedor;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
	 public function verProveedor(Request $request, $proveedor)
	 {   
	     $proveedor = Proveedor::findorFail($proveedor);
	       return view("proveedores.ver-proveedor")
	       ->withProveedor($proveedor)
	       ->withTitle($proveedor->nombre);
	 }
	 public function verInvoiceProveedor(Request $request, $invoice)
	 {   
	     $invoice = InvoiceProveedor::findorFail($invoice);
	     $proveedor = $invoice->proveedor;
	       return view("proveedores.ver-invoice-proveedor")
	       ->withProveedor($proveedor)
	       ->withInvoice($invoice)
	       ->withTitle($proveedor->nombre);
	 }
}
