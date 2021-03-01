@extends('layouts.main')

@section('title', 'Dashboard')

@section('additionalFileCSS')

@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Selamat Datang {{Session::get('_name')}}!</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>
    </div>
</div>
<!-- /Page Header -->

<div class="row" id="lot_statistic">
    
</div>
<hr>
<div class="row" id="lot_progress">
    
</div>
@endsection

@section('additionalFileJS')
    <script src="{{ asset('template/assets/js/chart.js') }}"></script>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
    let colors = [
        '#7cf962',
        '#d675be',
        '#7c3f4e',
        '#dad31e',
        '#ac9d33',
        '#6f9074',
        '#c43cc6',
        '#3986b6',
        '#9bf80e',
        '#b4af4c'
    ];
    lotSold();
    lotProgress();

    function lotSold() {
        $.ajax({
          url: BASE_URL+'/dashboard/lot_sold',
          type: 'GET',
          dataType: 'JSON',
          success: function(data, textStatus, jqXHR){
            let html = '';
            $.each(data, function(key, values) {
                html += '<div class="col-md-12 col-lg-6 col-xl-4 d-flex">';
                html += '    <div class="card flex-fill">';
                html += '        <div class="card-body">';
                html += '            <h4 class="card-title">'+key+'</h4>';
                html += '            <div class="statistics">';
                html += '                <div class="row">';
                html += '                    <div class="col-md-6 col-6 text-center">';
                html += '                        <div class="stats-box mb-4">';
                html += '                            <p>Jumlah Unit Total</p>';
                html += '                            <h3>'+values.total_unit+'</h3>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="col-md-6 col-6 text-center">';
                html += '                        <div class="stats-box mb-4">';
                html += '                            <p>Jumlah Unit Ready</p>';
                html += '                            <h3>'+values.total_unit_ready+'</h3>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '            </div>';

                let total_percentage = values.total_credit + values.total_cash + values.total_cash_in_stages;
                let credit_percentage = Math.ceil((total_percentage > 0 ? (values.total_credit / total_percentage) : 0) * 100);
                let cash_percentage = Math.ceil((total_percentage > 0 ? (values.total_cash / total_percentage) : 0) * 100);
                let cash_in_stages_percentage = Math.ceil((total_percentage > 0 ? (values.total_cash_in_stages / total_percentage) : 0) * 100);

                html += '            <div class="progress mb-4">';
                html += '                <div class="progress-bar bg-purple" role="progressbar" style="width: '+credit_percentage+'%" aria-valuenow="'+credit_percentage+'" aria-valuemin="0" aria-valuemax="100">'+credit_percentage+'%</div>';
                html += '                <div class="progress-bar bg-warning" role="progressbar" style="width: '+cash_percentage+'%" aria-valuenow="'+cash_percentage+'" aria-valuemin="0" aria-valuemax="100">'+cash_percentage+'%</div>';
                html += '                <div class="progress-bar bg-success" role="progressbar" style="width: '+cash_in_stages_percentage+'%" aria-valuenow="'+cash_in_stages_percentage+'" aria-valuemin="0" aria-valuemax="100">'+cash_in_stages_percentage+'%</div>';
                html += '            </div>';
                html += '            <div>';
                html += '                <p><i class="fa fa-dot-circle-o text-purple mr-2"></i>Terjual KPR <span class="float-right">'+values.total_credit+'</span></p>';
                html += '                <p><i class="fa fa-dot-circle-o text-warning mr-2"></i>Terjual Cash <span class="float-right">'+values.total_cash+'</span></p>';
                html += '                <p><i class="fa fa-dot-circle-o text-success mr-2"></i>Terjual Cash Bertahap <span class="float-right">'+values.total_cash_in_stages+'</span></p>';
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';
            });

            $('#lot_statistic').html(html);
          },
          error: function(jqXHR, textStatus, errorThrown){

          },
        });
    }

    function lotProgress() {
        $.ajax({
          url: BASE_URL+'/dashboard/lot_progress',
          type: 'GET',
          dataType: 'JSON',
          success: function(data, textStatus, jqXHR){
            console.log(data);
            let html = '';
            $.each(data, function(key, values) {
                html += '<div class="col-md-12 col-lg-6 col-xl-4 d-flex">';
                html += '    <div class="card flex-fill">';
                html += '        <div class="card-body">';
                html += '            <h4 class="card-title">Project '+key+'</h4>';
                html += '            <div class="statistics">';
                html += '                <div class="row">';
                // html += '                    <div class="col-md-6 col-6 text-center">';
                // html += '                        <div class="stats-box mb-4">';
                // html += '                            <p>Jumlah Unit Total</p>';
                // html += '                            <h3>'+values.total_unit+'</h3>';
                // html += '                        </div>';
                // html += '                    </div>';
                // html += '                    <div class="col-md-6 col-6 text-center">';
                // html += '                        <div class="stats-box mb-4">';
                // html += '                            <p>Jumlah Unit Ready</p>';
                // html += '                            <h3>'+values.total_unit_ready+'</h3>';
                // html += '                        </div>';
                // html += '                    </div>';
                html += '                </div>';
                html += '            </div>';

                let totalStep = 0;
                $.each(values, function(stepKey, stepVal) {
                    totalStep += stepVal.total;
                });

                html += '            <div class="progress mb-4">';
                let x = 0;
                $.each(values, function(stepKey, stepVal) {
                    let total_percentage = Math.ceil((totalStep > 0 ? (stepVal.total / totalStep) : 0) * 100);
                    // if (total_percentage > 0) {
                        html += '                <div class="progress-bar" style="background-color: '+colors[x]+'; width: '+total_percentage+'%" role="progressbar" aria-valuenow="'+total_percentage+'" aria-valuemin="0" aria-valuemax="100">'+total_percentage+'%</div>';
                    // }
                    x++;
                });
                html += '            </div>';
                html += '            <div>';

                let y = 0;
                $.each(values, function(stepKey, stepVal) {
                    html += '                <p><i class="fa fa-dot-circle-o mr-2" style="color: '+colors[y]+' !important"></i>'+stepVal.name+' <span class="float-right">'+stepVal.total+'</span></p>';
                    y++;
                });
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';
            });

            $('#lot_progress').html(html);
          },
          error: function(jqXHR, textStatus, errorThrown){

          },
        });
    }
</script>
@endsection