      <!-- Vendor JS Files -->
      <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
      <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
      <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
      <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
      <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
      <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    
      {{-- jquery --}}
      <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
      
      <!-- Template Main JS File -->
      <script src="{{ asset('assets/js/main.js') }}"></script>
      <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
      <script>
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
      </script>
      
      @stack('script')