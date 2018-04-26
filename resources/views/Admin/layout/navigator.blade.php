<section class="content-header">
    <h1>
        {{ $title }}
        <small>{{ $subTitle }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admindash"><i class="fa fa-dashboard"></i> 控制台</a></li>
        <li><a href="{{ $moduleUrl }}">{{ $moduleName }}</a></li>
        <li class="active">{{ $funcName }}</li>
    </ol>
</section>