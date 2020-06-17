@extends('daran::layouts.master')

@section('title')
    Dashboard
@stop

@section('content')
    @include('daran::layouts._messages')
    <div class="row">
        <div class="col-12">
            <h3>Dashboard</h3>
        </div>
    </div>
    @if($analyticsMostVisitedPages)
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-wrapper bordered px-3 py-4">
                    <h5>@lang('daran::common.most_viewed')</h5>
                    <canvas id="most-visited-pages" width="400" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-wrapper bordered px-3 py-4">
                    <h5>@lang('daran::common.trend_visitatori_view')</h5>
                    <canvas id="total-visitors-page-views" width="400" height="150"></canvas>
                </div>
            </div>
        </div>
    @endif


    @if($latestOrders)
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-wrapper bordered px-3 py-4">
                    <h5>@lang('daran::order.latest')</h5>
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>@lang('daran::order.number')</th>
                                <th>@lang('daran::order.date')</th>
                                <th>@lang('daran::order.status')</th>
                                <th>@lang('daran::order.amount')</th>
                                <th>@lang('daran::order.client')</th>
                                <th>@lang('daran::common.email')</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestOrders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->uuid}}</td>
                                    <td>{{$order->created_at->format('Y-m-d H:i')}}</td>
                                    <td>@lang('daran::order.status_text.'.$order->status)</td>
                                    <td>{{number_format($order->total,2,',','.')}}</td>
                                    <td>{{$order->user->full_name}}</td>
                                    <td>{{$order->user->email}}</td>
                                    <td><a href="{{route('admin.orders.show',['id'=>$order->id])}}" class="ico" data-icon="E"></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if($orderNumbers)
        <div class="row mb-4">
            <div class="col-12 col-md-6">
                <div class="content-wrapper bordered px-3 py-4">
                    <h5>@lang('daran::common.trend_ordini')</h5>
                    <canvas id="trend-orders" width="400" height="250"></canvas>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="content-wrapper bordered px-3 py-4">
                    <h5>@lang('daran::common.trend_fatturato')</h5>
                    <canvas id="trend-revenue" width="400" height="250"></canvas>
                </div>
            </div>
        </div>
    @endif


    @include('daran::layouts._modal_delete')
@endsection
@section('footer_scripts')
    @parent
    <script>
        @if($analyticsMostVisitedPages)
            var BARCHARTEXMPLE  = $('#most-visited-pages');
            var trand_inerventi = new Chart(BARCHARTEXMPLE, {
                type: 'horizontalBar',
                options: {
                    legend: {
                        display: false,
                    },
                },
                data: {
                    labels: [
                        @php
                            for($i = 0; $i<10; $i++){
                                echo '"'.$analyticsMostVisitedPages[$i]['pageTitle'].' ('.$analyticsMostVisitedPages[$i]['url'].')",';
                            }
                        @endphp
                    ],
                    datasets: [
                        {
                            fill: true,
                            lineTension: 0,
                            backgroundColor: "#79ADF5",
                            borderColor: '#79ADF5',
                            data: [
                                @php
                                    for($i = 0; $i<10; $i++){
                                        echo $analyticsMostVisitedPages[$i]['pageViews'].',';
                                    }
                                @endphp
                            ],
                            spanGaps: false
                        }
                    ]
                }
            });

            var LINECHARTEXMPLE    = $('#total-visitors-page-views');
            var most_visited_pages = new Chart(LINECHARTEXMPLE, {
                type: 'line',
                data: {
                    labels: [
                        @php
                            foreach($analyticsTotalVisitorsAndPageViews as $analyticsTotalVisitorsAndPageView){
                                echo '"'.date('d/m/Y',strtotime($analyticsTotalVisitorsAndPageView['date'])).'",';
                            }
                        @endphp
                    ],
                    datasets: [
                        {
                            label: "Visitatori",
                            fill: true,
                            lineTension: 0,
                            backgroundColor: "transparent",
                            borderColor: '#79ADF5',
                            data: [
                                @php
                                    foreach($analyticsTotalVisitorsAndPageViews as $analyticsTotalVisitorsAndPageView){
                                        echo $analyticsTotalVisitorsAndPageView['visitors'].',';
                                    }
                                @endphp
                            ],
                        },
                        {
                            label: "Pagine visitate",
                            fill: true,
                            lineTension: 0,
                            backgroundColor: "transparent",
                            borderColor: "#7858D0",
                            data: [
                                @php
                                    foreach($analyticsTotalVisitorsAndPageViews as $analyticsTotalVisitorsAndPageView){
                                        echo $analyticsTotalVisitorsAndPageView['pageViews'].',';
                                    }
                                @endphp
                            ],
                        }
                    ]
                }
            });
        @endif

        @if($orderNumbers)
        var to = $('#trend-orders');
        var cto = new Chart(to, {
            type: 'line',
            data: {
                labels: [
                    @php
                        foreach($labelMesi as $month){
                            echo '"'.$month.'",';
                        }
                    @endphp
                ],
                datasets: [
                    {
                        label: "Numero Ordini",
                        fill: true,
                        borderColor: '#79ADF5',
                        backgroundColor: '#b8d3fa',
                        data: [
                            @php
                                for($i=1;$i<=13;$i++){
                                    $field = 'M'.$i;
                                    echo $orderNumbers->$field.',';
                                }
                            @endphp
                        ],
                    },

                ]
            }
        });

        var tr = $('#trend-revenue');
        var ctr = new Chart(tr, {
            type: 'line',
            data: {
                labels: [
                    @php
                        foreach($labelMesi as $month){
                            echo '"'.$month.'",';
                        }
                    @endphp
                ],
                datasets: [
                    {
                        label: "Fatturato",
                        fill: true,
                        borderColor: '#7858D0',
                        backgroundColor: '#cfc3ee',
                        data: [
                            @php
                                for($i=1;$i<=13;$i++){
                                    $field = 'M'.$i;
                                    echo $orderAmounts->$field.',';
                                }
                            @endphp
                        ],
                    },

                ]
            }
        });
        @endif
    </script>
@endsection
