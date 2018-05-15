@extends('layouts.backend.master.index') 
@section('content')

<!-- Main row -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Pagos <small>Panel de control</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
			<li class="active">Pagos</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<h2>Quinta {{$payment->house}}</h2>
		<div class="panel panel-primary">
			<div class="panel-heading">Detalles del pago</div>
			<div class="col-md-6">
				<div class="panel-body"><span style="font-weight: bold;">Banco: </span>{{$payment->bank}}</div>
				<div class="panel-body"><span style="font-weight: bold;">C&oacute;digo de confirmaci&oacute;n: </span>{{$payment->confirmation_code}}</div>
				<div class="panel-body"><span style="font-weight: bold;">Fecha de pago: </span>{{$payment->date}}</div>
				<div class="panel-body"><span style="font-weight: bold;">Monto: </span>{{$payment->amount}}</div>
				<div class="panel-body"><span style="font-weight: bold;">Estado del pago: </span>
				@if($payment->status == 'PENDING')
						En revisión
					@elseif($payment->status == 'APPROVED')
						Aprobado
					@elseif($payment->status == 'REJECTED')
						Rechazado
				@endif
				</div>
				<div class="panel-body"><span style="font-weight: bold;">Tipo de pago: </span>
					@if($payment->type == 'DEPOSIT')
						Deposito
					@else()
						Transferencia
					@endif
				</div>
				<div class="panel-body"><span style="font-weight: bold;">Nota: </span>{{$payment->note}}</div>
			</div>
			<div class="col-md-12">
				<a class="panel-body" href="{{asset($payment->file)}}" target="_blank">
					<img src="{{asset($payment->file)}}" class="img-responsive">
				</a>
			</div>
		</div>
		<div class="col-md-12">
		<a href="{{URL::route('admin/payments/approve/', $payment->id)}}"><button class="btn btn-success">Aprobar</button></a>
		<a href="{{URL::route('admin/payments/reject/', $payment->id)}}"><button class="btn btn-danger">Rechazar</button></a>
		<a href="{{URL::route('admin/payments')}}"><button class="btn btn-primary">Volver</button></a>
		</div>
	</section>
	
</div>
@endsection
