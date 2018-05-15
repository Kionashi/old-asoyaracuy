<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Payment;
use Storage;
use App\Operation;
use App\OrganizationBalance;

class PaymentController extends BackendController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $payments = Payment::where('user_status','ENABLED')->get();
        return $this->view('pages.backend.payments.index')
            ->with('payments',$payments)
        ;
    }

    public function filteredIndex(Request $request) {
        
        $filter = $request->filter;
        switch ($filter) {
            case 'dates':
                $startingDate = $request->startingDate;
                $endingDate = $request->endingDate;
                $payments = Payment::where('user_status','ENABLED')
                    ->where('date','<',$endingDate)
                    ->where('date','>',$startingDate)
                    ->get()
                    ;
                break;
            case 'pending':
                $payments = Payment::where('user_status','ENABLED')
                    ->where('status','PENDING')
                    ->get()
                    ;
                break;
            default:
                $payments = Payment::where('user_status','ENABLED')->get();
                break;
        }
        return $this->view('pages.backend.payments.index')
            ->with('payments',$payments)
        ;
    }
    
    public function detail($id) {
        $payment = Payment::find($id);
        $url = Storage::disk('local')->url($payment->file);
        // echo('url = '. $url.'\n' );
        // echo ('///////////////////////file = '.$payment->file);
        // die;
        return $this->view('pages.backend.payments.detail')
            ->with('payment',$payment)
            ->with('url',$url)
        ;
    }
    
    public function approve($id) {
        // Approve payment
        $payment = Payment::approve($id);
        if($payment != null){   
            
            // Update balance
            $user = User::find($payment->user_id);
            $user->balance += $payment->amount;
            $user->save();
            
            
            $amount = $payment->amount;
            $concept = 'Cuota ordinaria aprobada';
            $details = 'Pago de cuota ordinaria';
            
            //Register a new Operation (in this case, an Income)
            Operation::add($amount,$concept,$details,'INCOME');
            
            return redirect()->route('admin/payments')
                ->with('message','Pago aprobado exitosamente')
            ;
        }else{

            return redirect()->route('admin/payments')
                ->with('message','No se encontro el pago')
            ;
        }
    }
    
    public function reject($id) {
        $payment = Payment::reject($id);
        if($payment != null){   
            return redirect()->route('admin/payments')
                ->with('message','Pago rechazado exitosamente')
            ;
        }else{

            return redirect()->route('admin/payments')
                ->with('message','No se encontro el pago')
            ;    
        }
    }
        
    
}
