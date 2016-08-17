@extends('master')

@section('customhead')
	{!! Html::style('css/user-signin.css') !!}
@endsection

@section('content')
<div class="header-offset"></div>
<section>
	<div id="user-authentification" class="row">
		<div id="user-authentification-signin" class="col-md-4">
			<h2>Sign in</h2>
			@if (isset($error))
				@if ($error['code'] == 'invalidemail')
				<h3>Cet utilisateur n'existe pas</h3>
				@elseif ($error['code'] == 'invalidpw')
				<h3>Mot de passe incorrect</h3>
				@endif
			@endif
			{!! Form::open(array('url' => 'signin', 'method' => 'post')) !!}
			<div class="form-group">
			{!! Form::label('email','Email') !!}
			{!! Form::text('email', null,array('class' => 'form-control', 'placeholder' => 'Enter email')) !!}
			</div>
			<div class="form-group">
			{!! Form::label('password','Password') !!}
			{!! Form::password('password',array('class' => 'form-control', 'placeholder' => 'Enter password')) !!}
			</div>
			<div class="form-group">
			{!! Form::submit('Sign in', array('class' => 'btn btn-primary')) !!}
			</div>
			{!! Form::close() !!}
		</div>
		<div id="user-authentification-signup" class="col-md-2"></div>
		<div id="user-authentification-signup" class="col-md-4">
			<h2>Sign up</h2>
			@if (isset($error))
				@if ($error['code'] == 'invalidemail')
				<h3>Cet utilisateur n'existe pas</h3>
				@elseif ($error['code'] == 'invalidpw')
				<h3>Mot de passe incorrect</h3>
				@endif
			@endif
			{!! Form::open(array('url' => 'signup', 'method' => 'post')) !!}
			<div class="form-group">
			{!! Form::label('email','Email') !!}
			{!! Form::text('email', null,array('class' => 'form-control', 'placeholder' => 'Enter email')) !!}
			</div>
			<div class="form-group">
			{!! Form::label('password','Password') !!}
			{!! Form::password('password',array('class' => 'form-control', 'placeholder' => 'Enter password')) !!}
			</div>
			<div class="form-group">
			{!! Form::label('password','Confirm Password') !!}
			{!! Form::password('password',array('class' => 'form-control', 'placeholder' => 'Enter password again')) !!}
			</div>
			<div class="form-group">
			{!! Form::submit('Sign up', array('class' => 'btn btn-primary')) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
	

</section>

@endsection

@section('localscript')
<script type="text/javascript">
	
</script>
@endsection
