@extends('layouts.mainlayout', ['title' => $job->GetTitle() . ' - Polycom'])

@section('content')
<div style="width: 100%; text-align: center">
	<h1>{{ $job->GetTitle() }}</h1>
	{{ $job->GetCompany() }}
	<h2>Responsibilities</h2>
	<div style="width: 30%; margin-left: 35%; text-align: left">
	{!! \App\Service\MarkdownParser::parse($job->GetResponsibilities()) !!}
	</div>
	<h2>Requirements</h2>
	<div style="width: 30%; margin-left: 35%; text-align: left">
	{!! \App\Service\MarkdownParser::parse($job->GetProjects()) !!}
	</div>
</div>
@endsection