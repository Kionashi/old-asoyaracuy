<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Operation;
use App\OrganizationBalance;

class OperationsController extends Controller
{
    public function index() {
    	
    	$operations = Operation::all();
    	
    	return view('pages.backend.operations.index')
    		->with('operations',$operations)
    		;
    }
    
    public function createIncome() {
    	
    	return view('pages.backend.operations.income.create');
    }
    
    public function createExpense() {
    	
    	return view('pages.backend.operations.expense.create');
    }
    
    public function store(Request $request){
    	
    	$validation = Validator::make($request->all(),[
            'amount' => 'required|numeric',
            'concept' => 'required|max:255',
            'details' => 'required',
        ]);

        if($validation->fails()){
    
            return redirect()->back()->withInput()->withErrors($validation->messages());
        }
        
    	$amount = $request->amount;
    	$concept = $request->concept;
    	$details = $request->details;
        $type = $request->type;
        
        //Register a new Operation
        $operation = Operation::add($amount,$concept,$details,$type);
        
        $this->storeAudit('Crear Operación', 'Operación ' . $operation->concept . ' del tipo '. $operation->type.' con monto '.$operation->amount.'cuyo id es '.$operation->id, $request->ip());
        
    	return $this->index();
    }
    
    public function editIncome($id) {
    	$operation = Operation::find($id);
    	
    	return view('pages.backend.operations.income.edit')
    		->with('operation',$operation)
    		;
    }
    
    public function editExpense($id) {
    	$operation = Operation::find($id);
    	
    	return view('pages.backend.operations.expense.edit')
    		->with('operation',$operation)
    		;
    }
    
    public function update(Request $request) {
    	
    	$validation = Validator::make($request->all(),[
            'amount' => 'required|numeric',
            'concept' => 'required|max:255',
            'details' => 'required'
        ]);

        if($validation->fails()){
    
            return redirect()->back()->withInput()->withErrors($validation->messages());
        }
        
        $id = $request->id;
    	$amount = $request->amount;
    	$concept = $request->concept;
    	$details = $request->details;
    	$type = $request->type;
    	
    	//Edit the operation, this returns the old operation (uneditted) for audit purposes
    	$oldOperation = Operation::edit($amount,$concept,$details,$type,$id);
    	
    	 $this->storeAudit('Modificar Operación', 'Operación ' . $oldOperation->concept . ' del tipo '. $oldOperation->type.' con monto '.$oldOperation->amount. 'Modificada por Operación '.$concept.' del tipo '. $type.' con monto '.$amount, $request->ip());
    	 
    	return $this->index();
    }
    
}
