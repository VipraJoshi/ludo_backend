<div class="pagetitle">
    <h1> @yield('pageTitle')</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">@if ($__env->yieldContent('pageTitle')) | @yield('pageTitle') @endif</li>
        </ol>
    </nav>
</div><!-- End Page Title -->