<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Payment;
use App\SpecialFee;
use App\OrganizationBalance;

class DashboardController extends BackendController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	

		$usersCount = User::where('status', '=', 'ENABLED')->count();
		$organizationBalance = OrganizationBalance::where('status','ACTIVE')->first();
        
        if(!$organizationBalance){
            $organizationBalance = new OrganizationBalance();
            $organizationBalance->balance = 0;
            $organizationBalance->status = 'ACTIVE';
            $organizationBalance>save();
        }
        
		$debtorsCount = User::getDebtors();
		
        // Show view
    	return $this->view('pages.backend.dashboard.index')
    		->with('debtorsCount',$debtorsCount)
    		->with('usersCount',$usersCount)
    		->with('organizationBalance',$organizationBalance->balance)
		;
    }

    

    
}
