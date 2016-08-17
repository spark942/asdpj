@extends('master')

@section('customhead')
	{!! Html::style('css/user-profile.css') !!}
@endsection

@section('content')
<section id="user-info">
	<h2>{{Auth::user()->name}}'s Dashboard</h2>
	@if ( substr(Auth::user()->rights, 0, 1) >= 1 )
	<h4><a href="/admin">Go to admin dashboard</a></h4>
	@endif
</section>

<section id="user-accounts">
	<h4><a href="/tot">Go to Tower of Trial page</a></h4>
	<p>Soon</p>
</section>
<!-- <section id="user-followers">
	<h4>My Followers</h4>
</section> -->
@endsection

@section('localscript')
<script type="text/javascript">
	
</script>
@endsection