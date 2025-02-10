<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    @include('layouts.header.meta', ['title' => 'Dashboard'])
    @include('layouts.style.style')

</head>
<!-- [Head] end -->

<!-- [Body] Start -->

<body>
    @include('layouts.header.header')
    @include('layouts.sidebar.sidebar')

    <!-- [ Main Content ] start -->
    <main id="main" class="main">
    @include('layouts.breadCrumbs.breadcrumb', ['crumbs'])

        @yield('content')

    </main><!-- End #main -->

    @include('layouts.footer.footer')
    @include('layouts.script.script')
</body>
<!-- [Body] end -->

</html>
