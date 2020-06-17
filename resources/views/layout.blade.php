<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Memary</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/css/mdb.min.css" rel="stylesheet">

    <!-- Styles -->
</head>
<body>

<div class="container" style="margin-top: 1%">
    <form action="{{route('cache.store')}}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="form-row">
            <div class="col-3 mb-3">
                <label for="validationCustom03">Memory Size</label>
                <input type="text" class="form-control" name="memory_size" required>
                @error('memory_size')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-3 mb-3">
                <label for="validationCustom04">type</label>
                <select class="custom-select" name="memory_type" required>
                    <option selected disabled>Choose</option>
                    <option value="kb">Kb</option>
                    <option value="mb">Mb</option>
                </select>
                @error('memory_type')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div class="form-row">
            <div class="col-3 mb-3">
                <label for="validationCustom03">Cache Size</label>
                <input type="text" class="form-control" name="cache_size" required>
                @error('cache_size')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-3 mb-3">
                <label for="validationCustom04">type</label>
                <select class="custom-select" name="cache_type" required>
                    <option selected disabled>Choose</option>
                    <option value="kb">Kb</option>
                    <option value="mb">Mb</option>
                </select>
                @error('cache_type')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>


        </div>
        <div class="form-row">

            <div class="col-3 mb-3">
                <label for="validationCustom03">block Size</label>
                <input type="text" class="form-control" name="block_size" required>
                @error('block_size')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            {{--                </div>--}}
            {{--                <div class="form-row">--}}

            <div class="col-3 mb-3">
                <label for="validationCustom03">cache access time</label>
                <input type="text" class="form-control" name="cache_access_time" required>
                @error('cache_access_time')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            {{--                </div>--}}
            {{--                <div class="form-row">--}}

            <div class="col-3 mb-3">
                <label for="validationCustom03">cache miss time</label>
                <input type="text" class="form-control" name="cache_miss_time" required>
                @error('cache_miss_time')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <input type="submit" class="btn btn-primary" value="make cache and memory">
    </form>
    <div class="row" style="margin-left: 1px">
        <div class="modal fade" id="modalSubscriptionForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Address</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('address.store')}}" method="POST">
                        @CSRF

                        <div class="modal-body mx-3">
                            <div class="md-form mb-4">
                                <i class="fas fa-envelope prefix grey-text"></i>
                                <input type="text" name="address" class="form-control validate">
                                @error('address')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label data-error="wrong" data-success="right" for="form2">Enter Address</label>

                            </div>
                            <div class="col-6 mb-3">
                                <label for="validationCustom04">Choose Cache</label>
                                <select class="custom-select" name="cache_id" required>
                                    <option selected disabled>Choose</option>
                                    @if($caches!=null);
                                    @foreach($caches as $cache)
                                        <option value="{{$cache->id}}">{{$cache->size}} {{$cache->type}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('cache_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <input type="submit" class="btn btn-indigo" value="check">
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal"
               data-target="#modalSubscriptionForm">
                Add or search address
            </a>
        </div>
        <div>
            <form action="{{route('address.show',1)}}" method="Get">
                @csrf
                <input type="submit" class="btn btn-warning" value="calculate">
            </form>
        </div>
        <div class="row" style="margin-left: 1px">
            <div class="modal fade" id="modalSubscriptionForm1" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Address</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('step')}}" method="POST">
                            @CSRF
                            <div class="modal-body mx-3">
                                <div class="md-form mb-4">
                                    <i class="fas fa-envelope prefix grey-text"></i>
                                    <input type="text" name="address" class="form-control validate">
                                    @error('address')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <label data-error="wrong" data-success="right" for="form2">Enter Address</label>
                                </div>

                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" class="btn btn-indigo" value="check">
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="" class="btn btn-danger btn-rounded mb-4" data-toggle="modal"
                   data-target="#modalSubscriptionForm1">
                    step
                </a>
            </div>
        </div>

    </div>

    {{--<div class="row">--}}

    @if($step==1)
        <div class="row">
            <table class="table">
                <thead class="black white-text">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">tag:  {{$tag}}</th>
                    <th scope="col">index:  {{$index}}</th>
                    <th scope="col">block offset :   {{$bo}}</th>
                    <th scope="col">status :   {{$status}}</th>
                </tr>
                </thead>
            </table>
        </div>

    @endif
    @if($calculate==1)
        <div class="row">
            <table class="table">
                <thead class="black white-text">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">T access:  {{$time}}</th>

                </tr>
                </thead>
            </table>
        </div>

    @endif
    {{--</div>--}}

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/js/mdb.min.js"></script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>

</html>
