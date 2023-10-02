<script type="module">
    import { Toast } from "{{ Vite::asset('resources/js/libs/sweetalert2.js') }}";

    window.addEventListener('load', e => {
            @if (session()->has('success'))
                Toast.fire({
                  icon: 'success',
                  title: @Js(session('success')),
                });
            @endif

            @if (session()->has('error'))
                Toast.fire({
                  icon: 'error',
                  title: @Js(session('error')),
                });
            @endif

            @if (session()->has('warning'))
                Toast.fire({
                  icon: 'warning',
                  title: @Js(session('warning')),
                });
            @endif

            @if (session()->has('info'))
                Toast.fire({
                  icon: 'info',
                  title: @Js(session('info')),
                });
            @endif
    });
</script>
