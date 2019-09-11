@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <h4>In this application you will 1) upload a .csv file and 2) view results in browser.</h4>
                </div>
                <div class="card-body">
                        <form action="/uploadfile" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="fileToUpload" id="exampleInputFile" onchange="document.getElementById('loading').style.display='block';form.submit();" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Please upload a valid .csv file. Size should not be more than 2MB.</small>
                            </div>
                        </form>
                       
                      @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif                                      
                    <span class="spinner-border text-primary" role="status" id="loading" style="display:none;">
                    </span>
                </div>
            </div>
            @if ($lastFile)
            <div class="card mt-3">
                <div class="card-header">
                  Last File Uploaded at {{$lastFile['lastModified']}} -- Found {{$lastFile['rows']}} Entries
                </div>
                <div class="card-body">   
               
                <table id="table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr class="success">
                            <th>Date</th>
                            <th>Client Id</th>
                            <th>Client</th>
                            <th>Deal id</th>
                            <th>Deal</th>
                            <th>Accepted</th>
                            <th>Refused</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deals as $d)
                        <tr>
                            <td>{{date('Y-m-d H:i',strtotime($d->hour))}}</td>
                            <td>{{$d->cid}}</td>
                            <td>{{$d->name}}</td>
                            <td>{{$d->cdid}}</td>
                            <td>{{$d->title}}</td>
                            <td class="text-right">{{$d->accepted}}</td>
                            <td class="text-right">{{$d->refused}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="success text-right font-weight-bold">
                            <td colspan="5">Totals:</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>  
                </div>
            </div>
            <script>
                $(document).ready(function() {
                var table = $('#table').DataTable( {
                    lengthChange: true,
                    buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    language: {
                        searchPlaceholder: "Client, Deal or Date"
                    },
                    'columnDefs': [
                        { targets: 1, visible: false },
                        { targets: 3, visible: false }
                    ],
                    iDisplayLength: -1,
                    "autoWidth": true,
                    "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api();
                        nb_cols = api.columns().nodes().length;
                        var j = 5;
                        while(j < nb_cols){
                            var pageTotal = api
                        .column( j, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return Number(a) + Number(b);
                        }, 0 );
                        // Update footer
                        $( api.column( j ).footer() ).html(pageTotal);
                                    j++;
                                } 
                            }
                        });
                        table.buttons().container()
                            .appendTo( '#table_wrapper .col-md-6:eq(0)' );
                    });
                    
                </script>
            @endif
        </div>
    </div>        
</div>
@endsection