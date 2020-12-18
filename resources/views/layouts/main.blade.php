<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Smarthr - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/font-awesome.min.css') }}">

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/line-awesome.min.css') }}">

    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/morris/morris.css') }}">

    <link rel="stylesheet" href="{{ asset('template/assets/plugins/sweetalert/sweetalert.css') }}">

    <link rel="stylesheet" href="{{ asset('template/assets/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/assets/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap-datetimepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/assets/css/dropify.min.css') }}">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
    <script type="text/javascript">
      let BASE_URL = '{{ url('/') }}'
    </script>
    <body>
      <div class="main-wrapper">
        <div class="header">
          @include('layouts.header')
        </div>

        <div class="sidebar" id="sidebar" style="width: 250px !important;">
          <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
              @include('layouts.menu.web')
            </div>
          </div>
        </div>

        <div class="page-wrapper">
          <div class="content container-fluid">
            @yield('content')
          </div>
        </div>
      </div>
      <!-- jQuery -->
      <script src="{{ asset('template/assets/js/jquery-3.2.1.min.js') }}"></script>

      <!-- Bootstrap Core JS -->
      <script src="{{ asset('template/assets/js/popper.min.js') }}"></script>
      <script src="{{ asset('template/assets/js/bootstrap.min.js') }}"></script>

      <!-- Slimscroll JS -->
      <script src="{{ asset('template/assets/js/jquery.slimscroll.min.js') }}"></script>

      <!-- Chart JS -->
      {{-- <script src="{{ asset('template/assets/plugins/morris/morris.min.js') }}"></script> --}}
      {{-- <script src="{{ asset('template/assets/plugins/raphael/raphael.min.js') }}"></script> --}}
      {{-- <script src="{{ asset('template/assets/js/chart.js') }}"></script> --}}

      <script src="{{ asset('template/assets/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('template/assets/js/dataTables.bootstrap4.min.js') }}"></script>

      <script src="{{ asset('template/assets/js/select2.min.js') }}"></script>

      <script src="{{ asset('template/assets/plugins/sweetalert/sweetalert.min.js') }}"></script>

      <script src="{{ asset('template/assets/js/moment.min.js') }}"></script>
      <script src="{{ asset('template/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

      <script src="{{ asset('template/assets/js/dropify.min.js') }}"></script>

      <script src="{{ asset('template/assets/js/accounting.min.js') }}"></script>

      <!-- Custom JS -->
      <script src="{{ asset('template/assets/js/app.js') }}"></script>

      @yield('additionalFileJS')

      <script type="text/javascript">
        $('.dropify').dropify();
        
        if ($("#datepicker-popup").length) {
          $('#datepicker-popup').datepicker({
            enableOnReadonly: true,
            todayHighlight: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
          });
        }

        function addSeparator(nStr, inD = '.', outD = '.', sep = '.') {
          nStr += '';
          var dpos = nStr.indexOf(inD);
          var nStrEnd = '';
          if (dpos != -1) {
            nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
            nStr = nStr.substring(0, dpos);
          }
          var rgx = /(\d+)(\d{3})/;
          while (rgx.test(nStr)) {
            nStr = nStr.replace(rgx, '$1' + sep + '$2');
          }
          return nStr + nStrEnd;
        }

        function formatDateIndo(str) {
          var dataMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
          
          if (str) {
            var result = str.split('-');

            var date = result[2];
            var month = dataMonth[parseInt(result[1]) - 1];
            var year = result[0];

            return date + ' ' + month + ' ' + year;
          }

          return str;
        }

        function formatDateTimeIndo(str) {
          var dataMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
          
          if (str) {
            var dateTime = str.split(' ');

            var result = dateTime[0].split('-');

            var date = result[2];
            var month = dataMonth[parseInt(result[1]) - 1];
            var year = result[0];

            return date + ' ' + month + ' ' + year + ' ' + dateTime[1];
          }

          return str;
        }

        var toHHMMSS = (secs) => {
            var sec_num = parseInt(secs, 10)
            var hours   = Math.floor(sec_num / 3600)
            var minutes = Math.floor(sec_num / 60) % 60
            var seconds = sec_num % 60

            return [hours,minutes,seconds]
                .map(v => v < 10 ? "0" + v : v)
                .filter((v,i) => v !== "00" || i > 0)
                .join(":")
        }

        function getAge(dateString) {
          var now = new Date();
          var today = new Date(now.getYear(),now.getMonth(),now.getDate());

          var yearNow = now.getYear();
          var monthNow = now.getMonth();
          var dateNow = now.getDate();

          var dob = new Date(dateString.substring(0,4),
                             dateString.substring(5,7)-1,                   
                             dateString.substring(8,10)                  
                             );

          var yearDob = dob.getYear();
          var monthDob = dob.getMonth();
          var dateDob = dob.getDate();
          var age = {};
          var ageString = "";
          var yearString = "";
          var monthString = "";
          var dayString = "";


          yearAge = yearNow - yearDob;

          if (monthNow >= monthDob)
            var monthAge = monthNow - monthDob;
          else {
            yearAge--;
            var monthAge = 12 + monthNow -monthDob;
          }

          if (dateNow >= dateDob)
            var dateAge = dateNow - dateDob;
          else {
            monthAge--;
            var dateAge = 31 + dateNow - dateDob;

            if (monthAge < 0) {
              monthAge = 11;
              yearAge--;
            }
          }

          age = {
              years: yearAge,
              months: monthAge,
              days: dateAge
              };

          if ( age.years > 1 ) yearString = " thn";
          else yearString = " thn";
          if ( age.months> 1 ) monthString = " bln";
          else monthString = " bln";
          if ( age.days > 1 ) dayString = " hr";
          else dayString = " hr";

          if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
            ageString = age.years + yearString + ", " + age.months + monthString + ", " + age.days + dayString;
          else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
            ageString = age.days + dayString;
          else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
            ageString = age.years + yearString + ". Selamat Ulang Tahun!";
          else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
            ageString = age.years + yearString + ", " + age.months + monthString;
          else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
            ageString = age.months + monthString + ", " + age.days + dayString;
          else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
            ageString = age.years + yearString + ", " + age.days + dayString;
          else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
            ageString = age.months + monthString;
          else ageString = "Oops! Could not calculate age!";

          return ageString;
        }

        function addCommas(nStr) {
            nStr += '';
            var x = nStr.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        function detectFloat(source) {
            let float = accounting.unformat(source);
            let posComma = source.indexOf('.');
            if (posComma > -1) {
                let posDot = source.indexOf(',');
                if (posDot > -1 && posComma > posDot) {
                    let germanFloat = accounting.unformat(source, '.');
                    if (Math.abs(germanFloat) > Math.abs(float)) {
                        float = germanFloat;
                    }
                } else {
                    // source = source.replace(/,/g, '.');
                    float = accounting.unformat(source, '.');
                }
            }
            return float;
        }

        function slugify(content) {
            return content.toLowerCase().replace(/ /g,'_').replace(/[^\w-]+/g,'');
        }

        function successModal(msg) {
          $('#success-modal').modal('show');
          $('#success-msg').text(msg);
        }

        function failModal(msg) {
          $('#fail-modal').modal('show');
          $('#err-msg').text(msg);
        }
      </script>
      @yield('additionalScriptJS')
    </body>
</html>