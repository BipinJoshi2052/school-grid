@extends('super-admin.layouts.admin')

@section('title')
Home
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Good Morning, {{ auth()->user()->name }}!
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="index.html">
                                    Dashboard
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>

    $(document).ready(function () {

        var data = {
            labels: ['Class 10', 'Class 9', 'Class 8', 'Class 7', 'Class 6', 'Class 5', 'Class 4', 'Class 3', 'Class 2', 'Class 1'],
            series: [
                [40, 38, 36, 40, 45, 50, 38, 36, 40, 45, 50]
            ]
        };

        var options = {
            axisX: {
                showGrid: false
            },
            seriesBarDistance: 1,
            chartPadding: {
                top: 15,
                right: 15,
                bottom: 5,
                left: 0
            },
            plugins: [
                Chartist.plugins.tooltip()
            ],
            width: '100%'
        };

        var responsiveOptions = [
            ['screen and (max-width: 640px)', {
                seriesBarDistance: 5,
                axisX: {
                    labelInterpolationFnc: function (value) {
                        return value[0];
                    }
                }
            }]
        ];
        // new Chartist.Bar('.net-income', data, options, responsiveOptions);

        });
</script>
@endsection