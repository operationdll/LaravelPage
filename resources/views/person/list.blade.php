<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Person</title>
        <!-- Bootstrap  CSS file -->
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <script src="{{asset('/resources/js/jquery.js')}}"></script>
        <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <script>
            function details(id){
                window.location = "{{url('detail')}}?id="+id;
            }
        </script>
    </head>
    <body>
    <div class="container">
        <div class="row">
        </br></br></br>
        </div>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-12 col-sm-12 col-lg-5">
                <nav class="navbar navbar-light bg-light">
                    <form class="form-inline" method="post" >
                        <input name='_token' type='hidden' value='{{csrf_token()}}'/>
                        <input name='page' type='hidden' value='1'/>
                        <font size="4px"><b>Nick name:</b></font><input name="name" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </nav>
            </div>
            <div class="col-lg-4"></div>
        </div>
        <div class="container">
            <h2>Users information</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>User id</th>
                    <th>Nick name</th>
                    <th>Email</th>
                    <th>Login time</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody id="clientList">
                @foreach($persons as $p)
                    <tr>
                        <td>
                            {{$p->id}}
                        </td>
                        <td>
                            {{$p->nickname}}
                        </td>
                        <td>
                            {{$p->email}}
                        </td>
                        <td>
                            {{$p->loginTime}}
                        </td>
                        <td>
                            <a href="javascript:details({{$p->id}})">Detail</a>
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="5" align="right">{{$persons->appends(request()->all())->links()}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </body>
</html>
