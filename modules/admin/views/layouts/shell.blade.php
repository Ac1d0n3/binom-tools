@extends('foundations.layouts.tools', [
    'adminShell' => true,
    'mainClass' => $mainClass ?? '',
    'viteEntries' => array_values(array_unique(array_merge(
        [
            'modules/sprint-planner/css/sprint-planner.css',
            'modules/admin/css/admin-hub.css',
            'modules/admin/js/admin-hub.js',
        ],
        $viteEntries ?? []
    ))),
])

@section('title')
    @yield('title')
@endsection

@section('meta_description')
    @yield('meta_description')
@endsection

@section('content')
    @yield('admin_content')
    @include('admin::partials.confirm-delete-modal')
@endsection
