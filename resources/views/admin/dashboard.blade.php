@extends('layouts.admin')

@section('title')
Home
@endsection

@section('content')
<style>
    .trial-period-message{
        margin-top: 10px;
    }
    .trial-period-message .alert{
        margin-bottom: 0;
    }
</style>

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                {{-- <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Good Morning, {{ auth()->user()->name }}!
                </h3> --}}
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
            @if ($data['institution_details']->package_type == 'trial')
                <div class="trial-period-message">
                    <div class="alert alert-primary" role="alert">
                        Welcome, {{ auth()->user()->name }}!<br>
                        You're currently in a 14-day Free Trial!<br>

                        Enjoy exploring the full features of our system during your free trial. If you'd like to upgrade to premium, please reach out to us at <a href="mailto:seatplanpro@gmail.com">seatplanpro@gmail.com</a>
                        to upgrade to a premium plan.

                        We’re here to assist you!<br><br>
                        <strong>Remaining days in your trial: {{ $data['remainingDays'] }} days</strong>
                    </div>
                </div>                
            @endif
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$data['faculty']}}</h2>
                                    {{-- <span class="badge bg-primary font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none">+18.33%</span> --}}
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Faculties
                                </h6>
                            </div>
                            <div class="ms-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end ">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{$data['batch']}}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">
                                    Batches
                                </h6>
                            </div>
                            <div class="ms-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end ">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$data['class']}}</h2>
                                    {{-- <span class="badge bg-danger font-12 text-white font-weight-medium rounded-pill ms-2 d-md-none d-lg-block">-18.33%</span> --}}
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Classes 
                                </h6>
                            </div>
                            <div class="ms-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card ">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{$data['section']}}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Sections</h6>
                            </div>
                            <div class="ms-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="globe"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Student Ratio</h4>
                        <div class="net-income mt-4 position-relative" style="height:294px;"></div>
                        <ul class="list-inline text-center mt-5 mb-2">
                            <li class="list-inline-item text-muted fst-italic">Classes</li>
                        </ul>
                    </div>
                </div>
            </div> --}}
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